# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [4.0.0](https://github.com/Team-MaRo/votifier-client-php/compare/3.0.0...4.0.0) (2026-06-30)


### ⚠ BREAKING CHANGES

* the namespace changed from D3strukt0r\VotifierClient to D3strukt0r\Votifier\Client, so every use statement must be updated. ServerType\ClassicVotifier is now Server\Votifier, ServerType\NuVotifier is Server\NuVotifier, ServerType\ServerTypeInterface is Server\ServerInterface, VoteType\ClassicVote is Vote\ClassicVote and VoteType\VoteInterface is Vote\VoteInterface; ServerConnection became the internal Socket and the Messages class was removed. Construction is now a fluent builder instead of positional constructors, you send on the server object directly with the variadic sendVote() and the Vote wrapper class is gone. Failures now throw dedicated typed exceptions (NotVotifierException, the NuVotifier family and the Exception\Socket transport exceptions) instead of a generic Exception, and a missing required field throws InvalidArgumentException.
* the namespace is now D3strukt0r\VotifierClient with ServerType\ and VoteType\ sub-namespaces, so every use statement must be updated. The single Vote class with a six-argument constructor is gone; you now build a ServerType and a VoteType, wrap them in a Vote and call send(). NuVotifier (protocol v1 and v2) is supported through ServerType\NuVotifier.

### ✨ Features

* add GenericServer and NuVotifier-specific exceptions ([0fdc13c](https://github.com/Team-MaRo/votifier-client-php/commit/0fdc13c52803ad6a3daaf0457a403ac9e1d0a819))
* add the initial Votifier client ([2c59024](https://github.com/Team-MaRo/votifier-client-php/commit/2c59024bae15a78063bed298053b47c6628e472f))
* add verifyConnection() to check a server before sending ([dc3760e](https://github.com/Team-MaRo/votifier-client-php/commit/dc3760efcfbe36ebb66a8210f40cd506b2ef68e2))
* replace the encryption padding with a more secure scheme ([3c3fc1f](https://github.com/Team-MaRo/votifier-client-php/commit/3c3fc1f7b6350ea3ea6e6de14f1509aa67b7b5a9))
* rework the client into ServerType, VoteType and ServerConnection classes ([29a3a48](https://github.com/Team-MaRo/votifier-client-php/commit/29a3a4875538773eece00e582c854408f0d03444))
* throw InvalidArgumentException when required fields are unset ([9e26f76](https://github.com/Team-MaRo/votifier-client-php/commit/9e26f76dc40894c9104430e645854d2fccfd0eb4))


### 🐛 Bug Fixes

* encrypt classic Votifier packets with PKCS[#1](https://github.com/Team-MaRo/votifier-client-php/issues/1) v1.5 padding ([ef4600b](https://github.com/Team-MaRo/votifier-client-php/commit/ef4600b3b84585c6ac5d3605e57122e1ca253ab6))
* fix the coverage reporting and Scrutinizer configuration ([ce0787e](https://github.com/Team-MaRo/votifier-client-php/commit/ce0787e668436fefb5f16b51cd6fc3ad6dafadb8))
* include the underlying reason and host when a connection can't be opened ([4aee88d](https://github.com/Team-MaRo/votifier-client-php/commit/4aee88d73c6e95c7303edd9cffbbc8a13810249e)), closes [#4](https://github.com/Team-MaRo/votifier-client-php/issues/4)
* resolve socket connection bugs and add PHP_CodeSniffer ([df15ece](https://github.com/Team-MaRo/votifier-client-php/commit/df15ece006eaa2d10fefaa100a89a2cd4788c1bf))
* support PHP 7.1 through 8.5 ([95bd6dd](https://github.com/Team-MaRo/votifier-client-php/commit/95bd6ddc8179cca1c21d2ebd8b3d13cf2011d744))
* time out and report when a server sends no response ([27bbfb8](https://github.com/Team-MaRo/votifier-client-php/commit/27bbfb88ec3a63459c6f1a73cdce74d690996f1b)), closes [#5](https://github.com/Team-MaRo/votifier-client-php/issues/5)
* use the correct NuVotifier server-error message and default the language to null ([dfdf118](https://github.com/Team-MaRo/votifier-client-php/commit/dfdf11857eba1cd24aeeb54bc33960b0d51683c0)), closes [#1](https://github.com/Team-MaRo/votifier-client-php/issues/1)


### ♻️ Refactoring

* apply PSR-12 with php-cs-fixer and PHP_CodeSniffer ([9743475](https://github.com/Team-MaRo/votifier-client-php/commit/9743475ad7793f14a3ee21a5858be0921a46e642))
* drop deprecated PHP versions and add type declarations ([a33754a](https://github.com/Team-MaRo/votifier-client-php/commit/a33754a0be1ef302d6c0ea91c0fc0b508bab05ef))
* move to the D3strukt0r\Votifier\Client namespace ([2978a21](https://github.com/Team-MaRo/votifier-client-php/commit/2978a21fc70a6315d7990b30622125e6c283b639))
* relocate the client into the Votifier\Client namespace ([2d728e4](https://github.com/Team-MaRo/votifier-client-php/commit/2d728e47faa449de37b5c11a4f4302acc40d44e9))
* remove unnecessary code and fix the code style ([09e32d5](https://github.com/Team-MaRo/votifier-client-php/commit/09e32d51e1580e86600a7846b14a62bcb4882d21))
* rename LICENSE.md to LICENSE.txt ([0a47e31](https://github.com/Team-MaRo/votifier-client-php/commit/0a47e31cf5919e97985dd26f0ba3e632894a2f13))
* reorganize into Server, Vote and a Socket abstraction ([224a1e8](https://github.com/Team-MaRo/votifier-client-php/commit/224a1e82ac879b269fb9be8980ba43c983ffa23f))
* replace inline errors with dedicated exception classes ([4c56fe2](https://github.com/Team-MaRo/votifier-client-php/commit/4c56fe2d71f43ba3c5fddd83d31a5b408e55e387))
* restructure the namespace and class layout ([8e28858](https://github.com/Team-MaRo/votifier-client-php/commit/8e28858c8a32dc56fd3ce0dad4fd20fcbde1adf3))
* turn Vote into a builder and send multiple votes per object ([0869026](https://github.com/Team-MaRo/votifier-client-php/commit/086902613bce005532d6b70396a51dd45c5473e0))


### 📚 Documentation

* add phpDocumentor API documentation ([972bc27](https://github.com/Team-MaRo/votifier-client-php/commit/972bc27789ccb934590e5a1d62a5f30113a0db3b))
* add the Read the Docs setup and README badges ([d6a810e](https://github.com/Team-MaRo/votifier-client-php/commit/d6a810e67ad1e35c2d2f5e3fd3337e2b1213348b))
* migrate the documentation from the README to Sphinx ([c04368e](https://github.com/Team-MaRo/votifier-client-php/commit/c04368ed8a568445d283677e4b58c068ff3f94bc))
* overhaul the Sphinx documentation ([919905f](https://github.com/Team-MaRo/votifier-client-php/commit/919905fc307289b0a20fdef1e39f84d62c90a019))
* rebuild the Sphinx documentation ([e871a14](https://github.com/Team-MaRo/votifier-client-php/commit/e871a14d37f638c4bb516a235b42b9d1c7f77c96))

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
