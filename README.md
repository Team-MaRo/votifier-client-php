# Votifier Client (for PHP)

This php script allows easy using of the Bukkit plugin Votifier

**Project**

[License][license] | Versions ([Packagist][packagist]) | Downloads ([Packagist][packagist]) | Required PHP Version
--- | --- | --- | ---
![License][license-icon] | Release: ![Version][release-icon]<br />Pre-Release: ![Version (including pre-releases)][pre-release-icon] | ![Downloads on Packagist][downloads-icon] | ![Required PHP version][php-icon]

**master**-branch (alias stable, latest)

[Travis][travis] | [Coveralls][coveralls] | [Scrutinizer][scrutinizer] | [SensioLabs][sensiolabs] | [Read the Docs][rtfd]
--- | --- | --- | --- | ---
![Build status][travis-master-icon] | ![Coveralls][coveralls-master-icon] | ![Scrutinizer code quality][scrutinizer-master-icon] | ![SymfonyInsight][insight-icon] | ![Docs build status][rtfd-master-icon]

**develop**-branch (alias nightly)

[Travis][travis] | [Coveralls][coveralls] | [Scrutinizer][scrutinizer] | [Read the Docs][rtfd]
--- | --- | --- | ---
![Build status][travis-develop-icon] | ![Coveralls][coveralls-develop-icon] | ![Scrutinizer code quality][scrutinizer-develop-icon] | ![Docs build status][rtfd-develop-icon]

[license]: https://github.com/D3strukt0r/votifier-client-php/blob/master/LICENSE.txt
[packagist]: https://packagist.org/packages/d3strukt0r/votifier-client
[travis]: https://travis-ci.com/D3strukt0r/votifier-client-php
[coveralls]: https://coveralls.io/github/D3strukt0r/Votifier-PHP-Client
[scrutinizer]: https://scrutinizer-ci.com/g/D3strukt0r/votifier-client-php/
[sensiolabs]: https://insight.symfony.com/projects/6056fd3d-1c44-4fa9-981d-c99ba2192c0f
[rtfd]: https://readthedocs.org/projects/votifier-client-php/

[license-icon]: https://img.shields.io/packagist/l/d3strukt0r/votifier-client
[release-icon]: https://img.shields.io/packagist/v/d3strukt0r/votifier-client
[pre-release-icon]: https://img.shields.io/packagist/v/d3strukt0r/votifier-client?include_prereleases
[downloads-icon]: https://img.shields.io/packagist/dt/d3strukt0r/votifier-client
[php-icon]: https://img.shields.io/packagist/php-v/d3strukt0r/votifier-client
[travis-master-icon]: https://img.shields.io/travis/com/D3strukt0r/votifier-client-php/master
[travis-develop-icon]: https://img.shields.io/travis/com/D3strukt0r/votifier-client-php/develop
[coveralls-master-icon]: https://img.shields.io/coveralls/github/D3strukt0r/Votifier-PHP-Client/master
[coveralls-develop-icon]: https://img.shields.io/coveralls/github/D3strukt0r/Votifier-PHP-Client/develop
[scrutinizer-master-icon]: https://img.shields.io/scrutinizer/quality/g/D3strukt0r/votifier-client-php/master
[scrutinizer-develop-icon]: https://img.shields.io/scrutinizer/quality/g/D3strukt0r/votifier-client-php/develop
[insight-icon]: https://insight.symfony.com/projects/6056fd3d-1c44-4fa9-981d-c99ba2192c0f/mini.svg
[rtfd-master-icon]: https://img.shields.io/readthedocs/votifier-client-php/stable
[rtfd-develop-icon]: https://img.shields.io/readthedocs/votifier-client-php/latest

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

The full documentation can be found on https://votifier-client-php-docs.manuele-vaccari.ch/

### Prerequisites

What things you need to install the software and how to install them

* PHP Project (PHP 7.1+)
* Minecraft server with the Votifier plugin

### Installing

Add the client using [Composer](http://getcomposer.org/).
```bash
composer require d3strukt0r/votifier-client
```

### Usage

Simply create an object with all informations

(For server with the classic Votifier plugins)
```php
<?php

use D3strukt0r\VotifierClient\ServerType\ClassicVotifier;
use D3strukt0r\VotifierClient\Vote;
use D3strukt0r\VotifierClient\VoteType\ClassicVote;

$serverType = new ClassicVotifier('127.0.0.1', null, 'MIIBIjANBgkq...');
$voteType = new ClassicVote($_GET['username'], 'Your vote list', $_SERVER['REMOTE_ADDR']);
$vote = new Vote($voteType, $serverType);
```

(For server which use the NuVotifier plugin (v1 protocol) (HINT: It's EXATCLY the same like method 1))
```php
<?php

use D3strukt0r\VotifierClient\ServerType\NuVotifier;
use D3strukt0r\VotifierClient\Vote;
use D3strukt0r\VotifierClient\VoteType\ClassicVote;

$serverType = new NuVotifier('127.0.0.1', null, 'MIIBIjANBgkq...');
$voteType = new ClassicVote($_GET['username'], 'Your vote list', $_SERVER['REMOTE_ADDR']);
$vote = new Vote($voteType, $serverType);
```

(For server which use the NuVotifier plugin with v2 protocol)
```php
<?php

use D3strukt0r\VotifierClient\ServerType\NuVotifier;
use D3strukt0r\VotifierClient\Vote;
use D3strukt0r\VotifierClient\VoteType\ClassicVote;

$serverType = new NuVotifier('127.0.0.1', null, null, true, '7j302r4n...');
$voteType = new ClassicVote($_GET['username'], 'Your vote list', $_SERVER['REMOTE_ADDR']);
$vote = new Vote($voteType, $serverType);
```

and then send it.
```php
<?php

try {
    $vote->send();
    // Connection created, and vote sent. Doesn't mean the server handled it correctly, but the client did.
} catch (\Exception $exception) {
    // Could not send Vote. Normally this happens when the client can't create a connection.
}
```

## Running the tests

Explain how to run the automated tests for this system

### Break down into end to end tests

Run test scripts

```bash
./vendor/bin/phpunit
```
```bash
.\vendor\bin\phpunit.bat
```

### Coding style tests and fixes

Download PHP CS Fixer from [here](https://cs.symfony.com/download/php-cs-fixer-v2.phar) and rename to `php-cs-fixer`.

```bash
./vendor/bin/php-cs-fixer fix
```
```bash
.\vendor\bin\php-cs-fixer.bat fix
```


Download PHP_CodeSniffer from [here](https://squizlabs.github.io/PHP_CodeSniffer/phpcs.phar) and [here](https://squizlabs.github.io/PHP_CodeSniffer/phpcbf.phar).

To see what mistakes exist in the code run:

```bash
./vendor/bin/phpcs
```
```bash
.\vendor\bin\phpcs.bat
```

And to fix it:

```bash
./vendor/bin/phpcbf
```
```bash
.\vendor\bin\phpcbf.bat
```

### Code documentation

Download `phpDocumentor.phar` from [here](http://phpdoc.org/phpDocumentor.phar) and rename to `phpdoc`.

```bash
php phpdoc -d ./src -t ./docs/api
```

## Built With

* [PHP](https://www.php.net/) - Programming Language
* [Composer](https://getcomposer.org/) - Dependency Management
* [PHPUnit](https://phpunit.de/) - Testing the code
* [Travis CI](https://travis-ci.com/) - Automatic CI (Testing)

## Contributing

Please read [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/D3strukt0r/votifier-client-php/tags). 

## Authors

* **Manuele Vaccari** - [D3strukt0r](https://github.com/D3strukt0r) - *Initial work*

See also the list of [contributors](https://github.com/D3strukt0r/votifier-client-php/contributors) who participated in this project.

## License

This project is licensed under the GNU General Public License v3.0 - see the [LICENSE.txt](LICENSE.txt) file for details

## Acknowledgments

* Hat tip to anyone whose code was used
* Inspiration
* etc
