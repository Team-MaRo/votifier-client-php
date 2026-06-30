#!/usr/bin/env python3
# /// script
# requires-python = ">=3.8"
# dependencies = ["beautifulsoup4"]
# ///
"""Regenerate docs/LICENSE.rst (CC BY-NC-SA 4.0) for the documentation.

Primary source: the official HTML legalcode on creativecommons.org, converted to
reStructuredText so the lettered/roman/numbered lists (a. / i. / A. / 1.) render
natively, with underlined defined terms, bold and hyperlinks preserved.

Fallback: if the site can't be reached, the committed plain-text legalcode
(docs/LICENSE.txt) is converted instead — same structure and lists, but without
the underline/bold that only exist in the HTML.

Run:  uv run docs/update-license.py   (RTD runs it in a pre_build job)
"""
import re
import string
import textwrap
import urllib.request
from pathlib import Path
from urllib.error import URLError

from bs4 import BeautifulSoup, NavigableString

SOURCE_URL = "https://creativecommons.org/licenses/by-nc-sa/4.0/legalcode"
HERE = Path(__file__).resolve().parent
OUTPUT = HERE / "LICENSE.rst"
LICENSE_TXT = HERE / "LICENSE.txt"
WRAP_WIDTH = 71
DIVS = ["notice-about-licenses-and-cc", "about-cc-and-license",
        "legal-code-body", "notice-about-cc-and-trademark"]
CHROME = {"About the license and Creative Commons", "About Creative Commons"}
# Heading underline characters, from the license's top level downward (they sit
# under copyright.rst's "=" title, so they must be distinct from "=").
HEADING_CHARS = "-~^\""

_ROMAN = [(1000, "m"), (900, "cm"), (500, "d"), (400, "cd"), (100, "c"),
          (90, "xc"), (50, "l"), (40, "xl"), (10, "x"), (9, "ix"),
          (5, "v"), (4, "iv"), (1, "i")]


def _alpha(idx):
    out, n = "", idx + 1
    while n > 0:
        n, r = divmod(n - 1, 26)
        out = string.ascii_lowercase[r] + out
    return out


def _roman(idx):
    n, out = idx + 1, ""
    for value, sym in _ROMAN:
        while n >= value:
            out, n = out + sym, n - value
    return out


def _marker(idx, list_type):
    # Always lowercase, matching the plain-text legalcode.
    return {"a": _alpha(idx), "i": _roman(idx)}.get((list_type or "1").lower(), str(idx + 1))


def _fix_href(href):
    if href.startswith("//"):
        href = "https:" + href
    elif href.startswith("/"):
        href = "https://creativecommons.org" + href
    return re.sub(
        r"https://wiki\.creativecommons\.org/wiki/Considerations_for_licensors_and_licensees#(Considerations_for_licens\w+)",
        r"https://wiki.creativecommons.org/\1", href)


# ---- HTML -> RST -----------------------------------------------------------

def inline_node(child):
    """A single inline node (text or element) as an RST string."""
    if isinstance(child, NavigableString):
        return str(child)
    name = child.name
    if name in ("strong", "b"):
        text = inline(child).strip()
        return text.upper() if len(text) > 100 else f"**{text}**"
    if name in ("em", "i"):
        return f"*{inline(child).strip()}*"
    if name == "u" or (name == "span" and "underline" in (child.get("style") or "")):
        return f":underline:`{inline(child).strip()}`"
    if name == "a":
        text, href = inline(child).strip(), child.get("href", "")
        return text if href.startswith("#") else f"`{text} <{_fix_href(href)}>`__"
    return inline(child)


def inline(node):
    """Inline content of a node's children as an RST string."""
    return re.sub(r"\s+", " ", "".join(inline_node(c) for c in node.children))


def render_list(ol, indent):
    items = [c for c in ol.children if getattr(c, "name", None) == "li"]
    list_type = ol.get("type", "1") if ol.name == "ol" else None
    blocks = []
    for idx, li in enumerate(items):
        marker = "- " if list_type is None else _marker(idx, list_type) + ". "
        lead, nested = [], []
        for child in li.children:
            if getattr(child, "name", None) in ("ol", "ul"):
                nested.append(child)
            else:
                lead.append(inline_node(child))
        lead_text = re.sub(r"\s+", " ", "".join(lead)).strip()
        hang = indent + " " * len(marker)
        out = textwrap.fill(lead_text, WRAP_WIDTH, initial_indent=indent + marker,
                            subsequent_indent=hang, break_long_words=False,
                            break_on_hyphens=False)
        for sub in nested:
            out += "\n\n" + render_list(sub, hang)
        blocks.append(out)
    return "\n\n".join(blocks)


def heading(text, level):
    text = to_ascii(text)  # so the underline length matches the final text
    return f"{text}\n{HEADING_CHARS[level] * len(text)}"


def html_to_rst(html):
    soup = BeautifulSoup(html, "html.parser")
    out = []
    title = None
    for div_id in DIVS:
        div = soup.find(id=div_id)
        if div is None:
            continue
        # Walk the div's meaningful blocks in document order.
        for node in div.descendants:
            name = getattr(node, "name", None)
            if name == "h2" and node.get("id") == "legal-code-title":
                title = inline(node).strip()
                # The formal "... Public License" heading is only inline text in
                # the HTML; emit it here (before "By exercising") as a heading.
                out.append(("formal", f"Creative Commons {title} Public License"))
            elif name in ("h2",) and inline(node).strip() in CHROME:
                continue
            elif name == "h2":
                out.append(("usingcc", inline(node).strip()))
            elif name == "h3":
                out.append(("section", inline(node).strip()))
            elif name == "p" and node.find_parent("li") is None:
                out.append(("p", inline(node).strip()))
            elif name == "ol" and node.find_parent(["ol", "ul"]) is None:
                out.append(("list", node))
    return assemble(title, out, source="html")


# ---- plain text -> RST (fallback) ------------------------------------------

SEP = re.compile(r"^[=-]{3,}\s*$")
SECTION = re.compile(r"^Section \d+ -- .+\.$")
ITEM = re.compile(r"^(\s*)((?:[A-Za-z]|[ivxlcdmIVXLCDM]+|\d+)\.)\s+(.*)$")


def txt_to_rst(text):
    out, title = [], None
    blocks, cur = [], []
    for line in text.splitlines():
        if not line.strip():
            if cur:
                blocks.append(cur)
                cur = []
        elif SEP.match(line):
            continue
        else:
            cur.append(line)
    if cur:
        blocks.append(cur)

    stack = []  # (text_col, rst_indent, marker_width)
    first = True
    for block in blocks:
        joined = " ".join(l.strip() for l in block)
        if first:
            title = joined
            first = False
            continue
        if joined == "Using Creative Commons Public Licenses":
            out.append(("usingcc", joined)); stack = []; continue
        if joined.startswith("Creative Commons Attribution") and joined.endswith("Public License"):
            out.append(("formal", joined)); stack = []; continue
        if len(block) == 1 and SECTION.match(block[0].strip()):
            out.append(("section", block[0].strip())); stack = []; continue
        m = ITEM.match(block[0])
        if m:
            ws, mark, head = m.groups()
            text_col = len(ws) + len(mark) + 1
            body = (head + " " + " ".join(l.strip() for l in block[1:])).strip()
            body = _linkify(body)
            while stack and stack[-1][0] > text_col:
                stack.pop()
            if stack and stack[-1][0] == text_col:
                rst_indent = stack[-1][1]; stack.pop()
            elif stack:
                rst_indent = stack[-1][1] + stack[-1][2]
            else:
                rst_indent = 0
            mw = len(mark) + 1
            stack.append((text_col, rst_indent, mw))
            pad = " " * rst_indent
            out.append(("li", textwrap.fill(
                mark + " " + body, WRAP_WIDTH, initial_indent=pad,
                subsequent_indent=pad + " " * mw,
                break_long_words=False, break_on_hyphens=False)))
        else:
            out.append(("p", _linkify(joined))); stack = []
    return assemble(title, out, source="txt")


def _linkify(text):
    text = re.sub(r"\b((?:wiki\.)?creativecommons\.org/[A-Za-z0-9/_-]+)",
                  r"`\1 <https://\1>`__", text)
    return re.sub(r"(?<![/`])\bcreativecommons\.org\b(?![/`])",
                  r"`creativecommons.org <https://creativecommons.org>`__", text)


# ---- shared assembly -------------------------------------------------------

def assemble(title, items, source):
    lines = [".. role:: underline", ""]
    if title:
        lines += [heading(title, 0), ""]
    for kind, value in items:
        if kind == "usingcc":
            lines += [heading(value, 1), ""]
        elif kind == "formal":
            lines += [heading(value, 1), ""]
        elif kind == "section":
            lines += [heading(value, 2), ""]
        elif kind == "p":
            lines += [textwrap.fill(value, WRAP_WIDTH, break_long_words=False,
                                    break_on_hyphens=False), ""]
        elif kind == "li":      # txt fallback: already-rendered RST list block
            lines += [value, ""]
        elif kind == "list":    # html: a bs4 <ol>
            lines += [render_list(value, ""), ""]
    # For the HTML source, lift the formal "... Public License" heading to sit
    # before "By exercising" (it's only inline text in the HTML); the txt has it.
    rst = "\n".join(lines)
    rst = re.sub(r"\n{3,}", "\n\n", rst).strip() + "\n"
    return to_ascii(rst)


_TYPOGRAPHY = {"“": '"', "”": '"', "‘": "'", "’": "'", "–": "--", "—": "--"}


def to_ascii(text):
    for fancy, plain in _TYPOGRAPHY.items():
        text = text.replace(fancy, plain)
    return text


def main():
    try:
        request = urllib.request.Request(SOURCE_URL, headers={"User-Agent": "Mozilla/5.0"})
        html = urllib.request.urlopen(request, timeout=30).read().decode("utf-8")
        rst = html_to_rst(html)
        src = "official HTML legalcode"
    except (URLError, OSError) as exc:
        rst = txt_to_rst(LICENSE_TXT.read_text(encoding="utf-8"))
        src = f"docs/LICENSE.txt (HTML fetch failed: {exc})"
    OUTPUT.write_text(rst, encoding="utf-8")
    print(f"Wrote {OUTPUT} from {src} ({len(rst.splitlines())} lines).")


if __name__ == "__main__":
    main()
