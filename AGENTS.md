# AGENTS.md

Guidance for AI agents (and humans) working in this repository.

## What this is

`d3strukt0r/votifier-client` (repo `Team-MaRo/votifier-client-php`) — a PHP library that
sends vote packets to a Minecraft server running the Votifier / NuVotifier plugin.
Published on Packagist; consumed via `composer require d3strukt0r/votifier-client`. PHP 7.1+.

## Commands

Everything runs through Composer scripts (see `composer.json` `scripts`):

| Task | Command |
|------|---------|
| Install deps | `composer install` |
| Run tests | `composer test` (→ `phpunit`) |
| Single test file | `vendor/bin/phpunit tests/Server/VotifierTest.php` |
| Single test method | `vendor/bin/phpunit --filter testMethodName` |
| Lint — PHP_CodeSniffer | `composer check` (→ `phpcs`) |
| Auto-fix — phpcbf | `composer fix` |
| Lint — PHP-CS-Fixer (dry run + diff) | `composer cs` |
| Auto-fix — PHP-CS-Fixer | `composer cs:fix` |
| Static analysis — PHPStan | `composer phpstan` |

**Three CI gates — a change must pass all three:**
- **PHP_CodeSniffer** (`.phpcs.xml`): the PHP-version-compatibility gate only —
  `PHPCompatibility` against `testVersion 7.1-`. It does **not** enforce PSR12; style is
  owned entirely by PHP-CS-Fixer (running both just made them fight over concat spacing,
  single-line empty bodies, etc.).
- **PHP-CS-Fixer** (`.php-cs-fixer.dist.php`): uses the shared `iwf-web/php-coding-standard`
  package (`@IWFWeb/standard` + `:risky` custom rule sets), stamps an MIT header onto
  every file via `header_comment` (the header's name/years come from `LICENSE.txt`, the
  email from `composer.json`), and overrides `trailing_comma_in_multiline` to `arrays`
  only — trailing commas in calls (PHP 7.3+) or declarations (8.0+) would break the
  library's PHP 7.1 support.
- **PHPStan** (`phpstan.dist.neon`): static analysis at **level 6** over `src/` and
  `tests/`. `phpstan/phpstan-phpunit` (auto-loaded via `phpstan/extension-installer`)
  teaches it PHPUnit mocks. Existing findings are grandfathered in `phpstan-baseline.neon`
  (regenerate with `vendor/bin/phpstan analyse --generate-baseline`), so only *new* issues
  fail CI.

All three (plus `composer validate`) run in the `style` job on a modern PHP; the test
matrix (PHP 7.1-8.5) runs only PHPUnit. PHP-CS-Fixer and PHPStan require PHP 7.4+, so the
matrix legs strip them before installing.

Coverage: not configured in `phpunit.xml.dist` (the `<coverage>` element only exists in
the PHPUnit 9.3+ schema and would break the PHPUnit 7/8 legs). CI drives it from the CLI
on the coverage leg — `phpunit --coverage-clover coverage/logs/clover.xml --coverage-filter src`
with Xdebug — and uploads the clover report; other legs run `--no-coverage`. Locally, add
the same flags (with a coverage driver) if you want a report.

Docker dev shell (`php:7.4-cli`, repo mounted at `/usr/src/app`):
`docker compose build` then `docker compose run --rm php composer test` (or any command).
`compose.yml` also defines a `spigot` service (the `d3strukt0r/spigot` image) and a
`web` service (the `public/` test UI). `docker compose up` starts both: Spigot exposes
Minecraft on 25565 and Votifier/NuVotifier on 8192 (server data — including a plugin you
drop in `./server/plugins` — persists under the gitignored `./server`), and the web UI
builds its own PHP 7.2 image (`public/Dockerfile`) and serves on 8000, reaching the plugin
via `VOTIFIER_HOST=spigot`.

## Library architecture (`src/`, namespace `D3strukt0r\Votifier\Client`)

Public flow: build a **Server**, build one or more **Vote** objects, call
`$server->sendVote($vote)`. All setters return `$this` (fluent builder); `sendVote()` is
variadic.

- **`Socket`** — thin wrapper over a raw TCP socket (`open`/`write`/`read`, auto-close in
  destructor). Servers own a `Socket`; tests inject a mock here.
- **`Server/`** — protocol layer.
  - `ServerInterface` — contract: host, port, public key, `verifyConnection()`, `sendVote()`.
  - `GenericServer` (abstract) — holds the `Socket`, host, port, public key + accessors.
  - `Votifier extends GenericServer` — **classic** Votifier (RSA-encrypted packet to the
    server's public key).
  - `NuVotifier extends Votifier` — NuVotifier v1 **and** v2 (`setProtocolV2(true)` +
    `setToken(...)` → challenge/HMAC instead of the RSA key). Reuses the classic packet path
    and overrides `verifyConnection`/`sendVote`/`preparePackage*`.
- **`Vote/`** — `VoteInterface` + `ClassicVote` (payload: username, service name, address,
  timestamp), also a fluent builder.
- **`Exception/`** — typed exceptions the caller catches: protocol-level at the root
  (`NotVotifierException`, the `NuVotifier*` family) and transport-level under
  `Exception/Socket/`. Setters throw `\InvalidArgumentException` when a required field is
  unset before a send. The README usage block is the canonical catch list.

## Tests (`tests/`)

- PSR-4 mapped to the **same** namespace root as the library (`autoload-dev`), mirroring `src/`.
- `tests/Server/votifier_private.key` / `votifier_public.key` are RSA fixtures exercising
  the encryption end to end — keep them in sync if the crypto changes.
- Stub properties are typed with the **intersection** `@var Foo&Stub` (e.g. `Socket&Stub`),
  not a union — PHPStan then resolves both the stubbed methods (`->method()`, from `Stub`)
  and the real class's methods, instead of flagging `method.notFound`.

## `public/` — end-to-end test server (develop only)

Standalone PHP app (`App\` namespace) that drives the library against a real server via
Symfony **5.4** components (so it runs on PHP 7.2.5+, matching its `php:7.2` image). It is
its **own composer island** (`public/composer.json`, path-requiring the library) so its
web-framework dependencies stay out of the published package; its lock is resolved inside
the container (no `config.platform.php`). Two ways to run it:
`docker compose up` (the `web` service builds `public/Dockerfile` and serves on 8000,
talking to the `spigot` service), or `bash public/start.sh` on the host (serves
`localhost:8000`, installs `public/vendor` on first run). `start.sh` honours `LISTEN`
(bind address, default `localhost:8000`) and the controllers honour `VOTIFIER_HOST`
(default `127.0.0.1`).

To exercise it, install a Votifier plugin into `server/plugins/` (use **NuVotifier** — the
classic vexsoftware Votifier only runs on Java 8, i.e. Minecraft ≤ 1.16.5; see
`docs/source/getting-started/votifier.rst` for links and the why). On first start the
plugin generates `server/plugins/Votifier/rsa/public.key` (classic RSA flow) and
`config.yml` with `tokens.default` (NuVotifier v2 flow) — the exact paths the controllers
read. The client encrypts with `openssl_public_encrypt`'s default PKCS#1 v1.5 padding,
which is what Votifier decrypts with (it uses `Cipher.getInstance("RSA")`, resolved by the
JDK's SunJCE provider to PKCS#1 v1.5).

For feedback that a vote was received: NuVotifier logs every vote to the console by
default; classic Votifier needs a listener — `tools/votifier-listener/` has one
(`bash tools/votifier-listener/build.sh` compiles it against the Votifier jar in a Java 8
container and drops the `.class` into `server/plugins/Votifier/listeners/`; restart spigot
to load it). It fires only after a vote successfully decrypts, so it doubles as proof the
server accepted the packet (a `BadPaddingException` instead means the client's public key
doesn't match the server's current keypair — recopy `rsa/public.key`). If the client can't
connect at all ("could not create a connection" / "couldn't be received") yet the plugin is
listed in `/plugins`, it failed to **enable** — check the server log (classic Votifier on a
modern JDK fails with the JAXB `NoClassDefFoundError` noted above).

## Documentation (`docs/`, Sphinx → Read the Docs)

- Build locally: `cd docs && make html` (needs `docs/requirements.txt`). Config in
  `docs/source/conf.py`; `copyright`/`author` are derived from `LICENSE.txt` at build time.
- **Community-health files are a git submodule:** `docs/source/_community` → `Team-MaRo/.github`.
  Code of Conduct / Contributing are pulled from there (not duplicated). After cloning run
  `git submodule update --init`; edit those documents in the central repo, not here.
- **The CC license page is generated, not committed:** `docs/update-license.py` converts
  the official CC BY-NC-SA 4.0 legalcode to reStructuredText `docs/LICENSE.rst` (gitignored)
  — native lettered/roman lists, underlined defined terms (`:underline:` role, styled by
  `_static/css/custom.css`), bold, links. It fetches the official HTML, **falling back** to
  the committed plain-text `docs/LICENSE.txt` if offline (fallback drops underline/bold).
  RTD runs it in a `build.jobs.pre_build` step; locally run `uv run docs/update-license.py`
  before `make html`.

## Licensing

Two separate licenses: the **code** is MIT (root `LICENSE.txt`), the **documentation** is
CC BY-NC-SA 4.0 (`docs/LICENSE.txt` + the generated `docs/LICENSE.md`).

## Conventions

- **Branching:** git-flow. `master` = stable/released, `develop` = nightly.
- **Releases:** release-please (`.github/release-please-config.json` + manifest) cuts
  `chore(master): release X.Y.Z` commits and tags from Conventional-Commit history.
- **Commits:** Conventional Commits; commits are GPG-signed by the maintainer.
- **Code ownership / CI:** `.github/CODEOWNERS` (`@Team-MaRo/CI`), Dependabot
  (`composer`, `github-actions`, `docker`) with auto-merge/validate workflows, and a
  `labeler` workflow. The published archive excludes dev/tooling files (`archive.exclude`).
