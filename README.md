# Votifier PHP Client

License | Versions | Downloads | Travis (master) | Travis (develop) | Coveralls | Scrutinizer | SensioLabs
--- | --- | --- | --- | --- | --- | --- | ---
[![Packagist](https://img.shields.io/packagist/l/d3strukt0r/votifier-client.svg)](https://github.com/D3strukt0r/votifier-client-php/blob/master/LICENSE) | Release<br />[![Packagist](https://img.shields.io/packagist/v/d3strukt0r/votifier-client.svg)](https://packagist.org/packages/d3strukt0r/votifier-client)<br />Pre-Release<br />[![Packagist Pre Release](https://img.shields.io/packagist/vpre/d3strukt0r/votifier-client.svg)](https://packagist.org/packages/d3strukt0r/votifier-client) | [![Packagist](https://img.shields.io/packagist/dt/d3strukt0r/votifier-client.svg)](https://packagist.org/packages/d3strukt0r/votifier-client) | [![Travis](https://img.shields.io/travis/D3strukt0r/votifier-client-php.svg)](https://travis-ci.org/D3strukt0r/votifier-client-php) | ... | ![Coveralls github](https://img.shields.io/coveralls/github/D3strukt0r/Votifier-PHP-Client) | [![Scrutinizer code quality (GitHub/Bitbucket)](https://img.shields.io/scrutinizer/quality/g/D3strukt0r/votifier-client-php.svg)](https://scrutinizer-ci.com/g/D3strukt0r/Votifier-PHP-Client/) | [![SymfonyInsight](https://insight.symfony.com/projects/6056fd3d-1c44-4fa9-981d-c99ba2192c0f/mini.svg)](https://insight.symfony.com/projects/6056fd3d-1c44-4fa9-981d-c99ba2192c0f)

This php script allows easy using of the Bukkit plugin Votifier

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

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

The full API documentation can be found on [https://d3strukt0r.github.io/votifier-client-php/api/]()

## Running the tests

Explain how to run the automated tests for this system

### Break down into end to end tests

Run test scripts

```bash
./vendor/bin/phpunit
```

### Coding style tests and fixes

Download PHP CS Fixer from [here](https://cs.symfony.com/download/php-cs-fixer-v2.phar) and rename to `php-cs-fixer`.

```bash
php php-cs-fixer fix
```

Download PHP_CodeSniffer from [here](https://squizlabs.github.io/PHP_CodeSniffer/phpcs.phar) and [here](https://squizlabs.github.io/PHP_CodeSniffer/phpcbf.phar).

To see what mistakes exist in the code run:

```bash
php phpcs
```

And to fix it:

```bash
php phpcbf
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
