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

use D3strukt0r\Votifier\Client\Server\Votifier;

$server = (new Votifier())
    ->setHost('127.0.0.1')
    ->setPublicKey('MIIBIjANBgkq...')
;
```

For the servers which use the NuVotifier plugin (v1 protocol) (HINT: It's NOT the same as method 1):

```php
<?php

use D3strukt0r\Votifier\Client\Server\NuVotifier;

$server = (new NuVotifier())
    ->setHost('127.0.0.1')
    ->setPublicKey('MIIBIjANBgkq...')
;
```

For the servers which use the NuVotifier plugin with v2 protocol:

```php
<?php

use D3strukt0r\Votifier\Client\Server\NuVotifier;

$server = (new NuVotifier())
    ->setHost('127.0.0.1')
    ->setProtocolV2(true)
    ->setToken('7j302r4n...')
;
```

Finally, just send it.

```php
<?php

use D3strukt0r\Votifier\Client\Exception\NotVotifierException;
use D3strukt0r\Votifier\Client\Exception\NuVotifierChallengeInvalidException;
use D3strukt0r\Votifier\Client\Exception\NuVotifierException;
use D3strukt0r\Votifier\Client\Exception\NuVotifierSignatureInvalidException;
use D3strukt0r\Votifier\Client\Exception\NuVotifierUnknownServiceException;
use D3strukt0r\Votifier\Client\Exception\NuVotifierUsernameTooLongException;
use D3strukt0r\Votifier\Client\Exception\Socket\NoConnectionException;
use D3strukt0r\Votifier\Client\Exception\Socket\PackageNotReceivedException;
use D3strukt0r\Votifier\Client\Exception\Socket\PackageNotSentException;
use D3strukt0r\Votifier\Client\Server\ServerInterface;
use D3strukt0r\Votifier\Client\Vote\ClassicVote;

$vote = (new ClassicVote())
    ->setUsername($_GET['username'])
    ->setServiceName('Your vote list')
    ->setAddress($_SERVER['REMOTE_ADDR'])
;

try {
    /** @var ServerInterface $server */
    $server->sendVote($vote);
    // Connection created, and vote sent. Doesn't mean the server handled it correctly, but the client did.
} catch (InvalidArgumentException $e) {
    // Not all variables that are needed have been set. See $e->getMessage() for all errors.
} catch (NoConnectionException $e) {
    // Could not create a connection (socket) to the specified server
} catch (PackageNotReceivedException $e) {
    // If the package couldn't be received, for whatever reason.
} catch (PackageNotSentException $e) {
    // If the package couldn't be send, for whatever reason.
} catch (NotVotifierException $e) {
    // The server didn't give a standard Votifier response
} catch (NuVotifierChallengeInvalidException $e) {
    // Specific for NuVotifier: The challenge was invalid (Shouldn't happen by default, but it's here in case).
} catch (NuVotifierSignatureInvalidException $e) {
    // Specific for NuVotifier: The signature was invalid (Shouldn't happen by default, but it's here in case).
} catch (NuVotifierUnknownServiceException $e) {
    // Specific for NuVotifier: A token can be specific for a list, so if the list isn't supposed to use the given token, this message appears.
} catch (NuVotifierUsernameTooLongException $e) {
    // Specific for NuVotifier: A username cannot be over 16 characters (Why? Don't ask me)
} catch (NuVotifierException $e) {
    // In case there is a new error message that wasn't added to the library, this will take care of that.
}
```

## Running the tests

Explain how to run the automated tests for this system

### Break down into end-to-end tests

Run test scripts

```shell
./vendor/bin/phpunit
```

### Coding style tests and fixes

To check if the code follows the PSR-12 standard, the library PHP_CodeSniffer has been add to the development environment, but you can also download it separately from
[here](https://squizlabs.github.io/PHP_CodeSniffer/phpcs.phar) and
[here](https://squizlabs.github.io/PHP_CodeSniffer/phpcbf.phar).

To see what mistakes exist in the code run:

```shell
./vendor/bin/phpcs
```

To fix the code:

```shell
./vendor/bin/phpcbf
```

This library already comes with `php-cs-fixer` but because it's impossible to set it up to follow the PSR-12 standard, it's not a requirement anymore. It's been left in the project only for additional styling information that might be applied. It can also be downloaded separately from
[here](https://cs.symfony.com/download/php-cs-fixer-v2.phar).

```shell
./vendor/bin/php-cs-fixer fix --diff --dry-run -v
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

### Old Code documentation

You can also create a documentation with [phpDocumentor](https://github.com/phpDocumentor/phpDocumentor). For that please downloaded at least v3+ for to work from [here](https://github.com/phpDocumentor/phpDocumentor/releases). And then you can just run it

```shell
curl -fsSL -o /usr/local/bin/phpdoc https://github.com/phpDocumentor/phpDocumentor/releases/download/v3.0.0-rc/phpDocumentor.phar
phpdoc
```

## Built With

-   [PHP](https://www.php.net) - Programming Language
-   [Composer](https://getcomposer.org) - Dependency Management
-   [PHPUnit](https://phpunit.de) - Testing the code
-   [Github Actions](https://github.com/features/actions) - Automatic CI (Testing)
-   [Read the docs](https://readthedocs.org) - Documentation

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
