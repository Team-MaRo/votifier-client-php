# Configuration file for the Sphinx documentation builder.
#
# This file only contains a selection of the most common options. For a full
# list see the documentation:
# https://www.sphinx-doc.org/en/master/usage/configuration.html

# -- Path setup --------------------------------------------------------------

# If extensions (or modules to document with autodoc) are in another directory,
# add these directories to sys.path here. If the directory is relative to the
# documentation root, use os.path.abspath to make it absolute, like shown here.
#
# import os
# import sys
# sys.path.insert(0, os.path.abspath('.'))

# -- Project information -----------------------------------------------------

import re
from pathlib import Path

project = 'votifier-client-php'

# Derive the copyright years and holder from LICENSE.txt (single source of
# truth) so they never have to be hand-updated here.
_license = (Path(__file__).resolve().parents[2] / 'LICENSE.txt').read_text(encoding='utf-8')
_match = re.search(r'Copyright \(c\)\s+([0-9][0-9,\-\s]*[0-9])\s+([A-Za-z].*)', _license)
if _match:
    _years, author = _match.group(1).strip(), _match.group(2).strip()
else:  # fall back rather than break the build if the license format changes
    _years, author = '2015-present', 'Manuele Vaccari'

copyright = f'{_years} {author}'

# The full version, including alpha/beta/rc tags
# release = 'x.x.x'

# -- General configuration ---------------------------------------------------

# Add any Sphinx extension module names here, as strings. They can be
# extensions coming with Sphinx (named 'sphinx.ext.*') or your custom
# ones.
extensions = [
    'sphinxcontrib.phpdomain',
    'myst_parser',
]

# Add any paths that contain templates here, relative to this directory.
templates_path = ['_templates']

# List of patterns, relative to source directory, that match files and
# directories to ignore when looking for source files.
# This pattern also affects html_static_path and html_extra_path.
# '_community' is the Team-MaRo/.github submodule — its Markdown files are pulled
# in via `.. include::`, so exclude them from being built as standalone pages.
exclude_patterns = ['Thumbs.db', '.DS_Store', '_community']

# The master toctree document.
master_doc = 'index'

# The name of the default domain.
primary_domain = 'php'

# -- Options for HTML output -------------------------------------------------

# The theme to use for HTML and HTML Help pages.  See the documentation for
# a list of builtin themes.
#
html_theme = 'sphinx_rtd_theme'

# Styles the `:underline:` role used by the generated license (RST has no native
# underline); see docs/source/_static/css/custom.css.
html_static_path = ['_static']
html_css_files = ['css/custom.css']
