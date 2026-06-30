# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [3.0.1](https://github.com/Team-MaRo/votifier-client-php/compare/3.0.0...3.0.1) (2026-06-30)


### 🐛 Bug Fixes

* encrypt classic Votifier packets with PKCS[#1](https://github.com/Team-MaRo/votifier-client-php/issues/1) v1.5 padding ([ef4600b](https://github.com/Team-MaRo/votifier-client-php/commit/ef4600b3b84585c6ac5d3605e57122e1ca253ab6))
* include the underlying reason and host when a connection can't be opened ([4aee88d](https://github.com/Team-MaRo/votifier-client-php/commit/4aee88d73c6e95c7303edd9cffbbc8a13810249e)), closes [#4](https://github.com/Team-MaRo/votifier-client-php/issues/4)
* support PHP 7.1 through 8.5 ([95bd6dd](https://github.com/Team-MaRo/votifier-client-php/commit/95bd6ddc8179cca1c21d2ebd8b3d13cf2011d744))
* time out and report when a server sends no response ([27bbfb8](https://github.com/Team-MaRo/votifier-client-php/commit/27bbfb88ec3a63459c6f1a73cdce74d690996f1b)), closes [#5](https://github.com/Team-MaRo/votifier-client-php/issues/5)


### 📚 Documentation

* overhaul the Sphinx documentation ([919905f](https://github.com/Team-MaRo/votifier-client-php/commit/919905fc307289b0a20fdef1e39f84d62c90a019))

## [3.0.0](https://github.com/Team-MaRo/votifier-client-php/compare/2.1.2...3.0.0) (2020-10-26)


### ⚠ BREAKING CHANGES

* **Namespace** changed from `D3strukt0r\VotifierClient` to `D3strukt0r\Votifier\Client`. Update every `use` statement.
* **Classes were moved and renamed:**
  * `ServerType\ClassicVotifier` → `Server\Votifier`
  * `ServerType\NuVotifier` → `Server\NuVotifier`
  * `ServerType\ServerTypeInterface` → `Server\ServerInterface`
  * `VoteType\ClassicVote` → `Vote\ClassicVote`
  * `VoteType\VoteInterface` → `Vote\VoteInterface`
  * `ServerConnection` → `Socket` (internal), and the `Messages` class was removed.
* **Construction is now a fluent builder** (no more positional constructors), and you send on the server object directly — the `Vote` wrapper class is gone. `sendVote()` is variadic, so several votes can be sent at once:

  ```php
  // before (2.x)
  $serverType = new ClassicVotifier('127.0.0.1', null, $publicKey);
  $voteType = new ClassicVote($username, 'Your vote list', $ip);
  (new Vote($voteType, $serverType))->send();

  // after (3.0.0)
  use D3strukt0r\Votifier\Client\Server\Votifier;
  use D3strukt0r\Votifier\Client\Vote\ClassicVote;

  $server = (new Votifier())->setHost('127.0.0.1')->setPublicKey($publicKey); // ->setPort() optional, defaults to 8192
  $vote = (new ClassicVote())->setUsername($username)->setServiceName('Your vote list')->setAddress($ip);
  $server->sendVote($vote);
  ```

  For NuVotifier v2: `(new NuVotifier())->setHost('127.0.0.1')->setProtocolV2(true)->setToken('...')`.
* **Failures now throw dedicated typed exceptions** instead of a generic `\Exception`. Catch what you need: `Exception\NotVotifierException`; the NuVotifier family `Exception\NuVotifierChallengeInvalidException`, `Exception\NuVotifierSignatureInvalidException`, `Exception\NuVotifierUnknownServiceException`, `Exception\NuVotifierUsernameTooLongException`; and the transport-level `Exception\Socket\NoConnectionException`, `Exception\Socket\PackageNotSentException`, `Exception\Socket\PackageNotReceivedException`. A missing required field throws `\InvalidArgumentException`.
* `verifyConnection()` is available on a server to check reachability before sending.


### ♻️ Refactoring

* redesign Vote/Server API and rename to D3strukt0r\Votifier\Client ([2978a21](https://github.com/Team-MaRo/votifier-client-php/commit/2978a21fc70a6315d7990b30622125e6c283b639))
* reorganize Server, Vote and Exception class layout ([224a1e8](https://github.com/Team-MaRo/votifier-client-php/commit/224a1e82ac879b269fb9be8980ba43c983ffa23f))
* adopt PSR-12 and add dedicated exception classes ([9743475](https://github.com/Team-MaRo/votifier-client-php/commit/9743475ad7793f14a3ee21a5858be0921a46e642))

## [2.1.2](https://github.com/Team-MaRo/votifier-client-php/compare/2.1.1...2.1.2) (2020-08-31)


### 🧹 Chores

* migrate CI from Travis CI to GitHub Actions and fix coverage reporting ([67165e1](https://github.com/Team-MaRo/votifier-client-php/commit/67165e178c32172395bd364ad13d5ac00e566c1e))

## [2.1.1](https://github.com/Team-MaRo/votifier-client-php/compare/2.1.0...2.1.1) (2020-04-25)


### 🐛 Bug Fixes

* use correct NuVotifier server-error message and default language to null ([dfdf118](https://github.com/Team-MaRo/votifier-client-php/commit/dfdf11857eba1cd24aeeb54bc33960b0d51683c0))


### 📚 Documentation

* migrate documentation from README to Sphinx ([c04368e](https://github.com/Team-MaRo/votifier-client-php/commit/c04368ed8a568445d283677e4b58c068ff3f94bc))

## [2.1.0](https://github.com/Team-MaRo/votifier-client-php/compare/2.0.0...2.1.0) (2020-03-09)


### 🐛 Bug Fixes

* resolve socket connection bugs and add PHP_CodeSniffer ([df15ece](https://github.com/Team-MaRo/votifier-client-php/commit/df15ece006eaa2d10fefaa100a89a2cd4788c1bf))


### ♻️ Refactoring

* rename LICENSE.md to LICENSE.txt ([0a47e31](https://github.com/Team-MaRo/votifier-client-php/commit/0a47e31cf5919e97985dd26f0ba3e632894a2f13))
* drop deprecated PHP versions and add type declarations ([a33754a](https://github.com/Team-MaRo/votifier-client-php/commit/a33754a0be1ef302d6c0ea91c0fc0b508bab05ef))

## [2.0.0](https://github.com/Team-MaRo/votifier-client-php/compare/1.0.0...2.0.0) (2018-04-24)


### ⚠ BREAKING CHANGES

* **Namespace** changed from `Votifier\Client` to `D3strukt0r\VotifierClient` (with `ServerType\` and `VoteType\` sub-namespaces). Update every `use` statement.
* **The single `Vote` class with a six-argument constructor was removed.** You now compose a server type, a vote type and a `Vote` wrapper, then call `send()`:

  ```php
  // before (1.x)
  use Votifier\Client\Vote;
  (new Vote('127.0.0.1', 8192, $publicKey, $username, 'My list', $ip))->send();

  // after (2.0.0)
  use D3strukt0r\VotifierClient\ServerType\ClassicVotifier;
  use D3strukt0r\VotifierClient\VoteType\ClassicVote;
  use D3strukt0r\VotifierClient\Vote;

  $serverType = new ClassicVotifier('127.0.0.1', null, $publicKey); // null port defaults to 8192
  $voteType = new ClassicVote($username, 'My list', $ip);
  (new Vote($voteType, $serverType))->send();
  ```

* **NuVotifier is now supported** via `ServerType\NuVotifier($host, $port, $publicKey, $protocolV2 = false, $token = null)` — pass `true` and a token for the v2 protocol.
* Sending still throws a generic `\Exception` on failure (typed exceptions arrive in 3.0.0).

## [1.0.0](https://github.com/Team-MaRo/votifier-client-php/compare/0.0.1...1.0.0) (2018-04-22)


### 🧹 Chores

* refine the Composer configuration and project files ([923994f](https://github.com/Team-MaRo/votifier-client-php/commit/923994f2aadaf06316972d03400d2de62326a31b))
* rename the license to LICENSE.md and stop tracking IDE project files ([90f3012](https://github.com/Team-MaRo/votifier-client-php/commit/90f3012b7caa7682fff54ca75688b90196c35f1e))

## 0.0.1 (2016-03-13)


### ✨ Features

* add Votifier client and convert to a Composer package ([2c59024](https://github.com/Team-MaRo/votifier-client-php/commit/2c59024bae15a78063bed298053b47c6628e472f))


### ♻️ Refactoring

* restructure namespace and class layout ([8e28858](https://github.com/Team-MaRo/votifier-client-php/commit/8e28858c8a32dc56fd3ce0dad4fd20fcbde1adf3))
* relocate client classes into the Votifier\Client namespace ([2d728e4](https://github.com/Team-MaRo/votifier-client-php/commit/2d728e47faa449de37b5c11a4f4302acc40d44e9))
