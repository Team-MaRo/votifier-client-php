# Votifier Client (for PHP)

This php script allows easy using of the Bukkit plugin Votifier

Project

[![License](https://img.shields.io/github/license/D3strukt0r/votifier-client-php)][license]
[![Version](https://img.shields.io/packagist/v/d3strukt0r/votifier-client?label=latest%20release)][packagist]
[![Version (including pre-releases)](https://img.shields.io/packagist/v/D3strukt0r/votifier-client?include_prereleases&label=latest%20pre-release)][packagist]
[![Downloads on Packagist](https://img.shields.io/packagist/dt/d3strukt0r/votifier-client)][packagist]
[![Required PHP version](https://img.shields.io/packagist/php-v/d3strukt0r/votifier-client)][packagist]

master-branch (alias stable, latest)

[![GH Action CI/CD](https://github.com/D3strukt0r/votifier-client-php/workflows/CI/CD/badge.svg?branch=master)][gh-action]
[![Coveralls](https://img.shields.io/coveralls/github/D3strukt0r/votifier-client-php/master)][coveralls]
[![Scrutinizer build status](https://img.shields.io/scrutinizer/build/g/D3strukt0r/votifier-client-php/master?label=scrutinizer%20build)][scrutinizer]
[![Scrutinizer code quality](https://img.shields.io/scrutinizer/quality/g/D3strukt0r/votifier-client-php/master?label=scrutinizer%20code%20quality)][scrutinizer]
[![Codacy grade](https://img.shields.io/codacy/grade/d97c7e16f5d24924b39f9776eeb02259/master?label=codacy%20code%20quality)][codacy]
[![Docs build status](https://img.shields.io/readthedocs/votifier-client-php/stable)][rtfd]

develop-branch (alias nightly)

[![GH Action CI/CD](https://github.com/D3strukt0r/votifier-client-php/workflows/CI/CD/badge.svg?branch=develop)][gh-action]
[![Coveralls](https://img.shields.io/coveralls/github/D3strukt0r/votifier-client-php/develop)][coveralls]
[![Scrutinizer build status](https://img.shields.io/scrutinizer/build/g/D3strukt0r/votifier-client-php/develop?label=scrutinizer%20build)][scrutinizer]
[![Scrutinizer code quality](https://img.shields.io/scrutinizer/quality/g/D3strukt0r/votifier-client-php/develop?label=scrutinizer%20code%20quality)][scrutinizer]
[![Codacy grade](https://img.shields.io/codacy/grade/d97c7e16f5d24924b39f9776eeb02259/develop?label=codacy%20code%20quality)][codacy]
[![Docs build status](https://img.shields.io/readthedocs/votifier-client-php/latest)][rtfd]

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

The full documentation can be found [here](https://votifier-client-php.readthedocs.io).

### Prerequisites

What things you need to install the software and how to install them

-   PHP Project (PHP 7.1+)
-   Minecraft server with the Votifier plugin

### Installing

Add the client using [Composer](http://getcomposer.org/).

```shell
composer require d3strukt0r/votifier-client
```

### Usage

Simply create an object with all information

For the servers with the classic Votifier plugins:

```php
<?php

use D3strukt0r\VotifierClient\Server\Votifier;
use D3strukt0r\VotifierClient\Vote;
use D3strukt0r\VotifierClient\Vote\ClassicVote;

$serverType = new Votifier('127.0.0.1', null, 'MIIBIjANBgkq...');
$voteType = new ClassicVote($_GET['username'], 'Your vote list', $_SERVER['REMOTE_ADDR']);
$vote = new Vote($voteType, $serverType);
```

For the servers which use the NuVotifier plugin (v1 protocol) (HINT: It's EXACTLY the same as method 1):

```php
<?php

use D3strukt0r\VotifierClient\Server\NuVotifier;
use D3strukt0r\VotifierClient\Vote;
use D3strukt0r\VotifierClient\Vote\ClassicVote;

$serverType = new NuVotifier('127.0.0.1', null, 'MIIBIjANBgkq...');
$voteType = new ClassicVote($_GET['username'], 'Your vote list', $_SERVER['REMOTE_ADDR']);
$vote = new Vote($voteType, $serverType);
```

For the servers which use the NuVotifier plugin with v2 protocol:

```php
<?php

use D3strukt0r\VotifierClient\Server\NuVotifier;
use D3strukt0r\VotifierClient\Vote;
use D3strukt0r\VotifierClient\Vote\ClassicVote;

$serverType = new NuVotifier('127.0.0.1', null, null, true, '7j302r4n...');
$voteType = new ClassicVote($_GET['username'], 'Your vote list', $_SERVER['REMOTE_ADDR']);
$vote = new Vote($voteType, $serverType);
```

Finally, just send it.

```php
<?php

try {
    $vote->send();
    // Connection created, and vote sent. Doesn't mean the server handled it correctly, but the client did.
} catch (Exception $exception) {
    // Could not send Vote. Normally this happens when the client can't create a connection.
}
```

## Running the tests

Explain how to run the automated tests for this system

### Break down into end-to-end tests

Run test scripts

```shell
./vendor/bin/phpunit
```

```powershell
.\vendor\bin\phpunit.bat
```

### Coding style tests and fixes

This libary already comes with `php-cs-fixer` but you can also download it from
[here](https://cs.symfony.com/download/php-cs-fixer-v2.phar) and rename to `php-cs-fixer`.

```shell
./vendor/bin/php-cs-fixer fix
```

```powershell
.\vendor\bin\php-cs-fixer.bat fix
```

This libary already comes with PHP_CodeSniffer but you can also download it from
[here](https://squizlabs.github.io/PHP_CodeSniffer/phpcs.phar) and
[here](https://squizlabs.github.io/PHP_CodeSniffer/phpcbf.phar).

To see what mistakes exist in the code run:

```shell
./vendor/bin/phpcs
```

```powershell
.\vendor\bin\phpcs.bat
```

And to fix it:

```shell
./vendor/bin/phpcbf
```

```powershell
.\vendor\bin\phpcbf.bat
```

### Code documentation

Install Python v3 from [here](https://www.python.org/downloads/).

Install `Sphinx` as documented [here](https://www.sphinx-doc.org/en/master/usage/installation.html).

```shell
pip install -U sphinx
```

To build the docs:

```shell
cd docs
pip install -r requirements.txt
make html
```

## Built With

-   [PHP](https://www.php.net/) - Programming Language
-   [Composer](https://getcomposer.org/) - Dependency Management
-   [PHPUnit](https://phpunit.de/) - Testing the code
-   [Github Actions](https://github.com/features/actions) - Automatic CI (Testing)

## Contributing

Please read [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/D3strukt0r/votifier-client-php/tags).

## Authors

-   **Manuele Vaccari** - [D3strukt0r](https://github.com/D3strukt0r) - _Initial work_

See also the list of [contributors](https://github.com/D3strukt0r/votifier-client-php/contributors) who participated in this project.

## License

This project is licensed under the GNU General Public License v3.0 - see the [LICENSE.txt](LICENSE.txt) file for details

## Acknowledgments

-   Hat tip to anyone whose code was used
-   Inspiration
-   etc

[license]: https://github.com/D3strukt0r/votifier-client-php/blob/master/LICENSE.txt
[packagist]: https://packagist.org/packages/d3strukt0r/votifier-client
[gh-action]: https://github.com/D3strukt0r/votifier-client-php/actions
[coveralls]: https://coveralls.io/github/D3strukt0r/votifier-client-php
[scrutinizer]: https://scrutinizer-ci.com/g/D3strukt0r/votifier-client-php/
[codacy]: https://www.codacy.com/manual/D3strukt0r/votifier-client-php
[rtfd]: https://readthedocs.org/projects/votifier-client-php/
