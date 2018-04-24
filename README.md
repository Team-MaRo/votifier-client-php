Votifier PHP Client
=================
[![Packagist](https://img.shields.io/packagist/v/d3strukt0r/votifier-client.svg)](https://packagist.org/packages/d3strukt0r/votifier-client)
[![Packagist Pre Release](https://img.shields.io/packagist/vpre/d3strukt0r/votifier-client.svg)](https://packagist.org/packages/d3strukt0r/votifier-client)
[![Packagist](https://img.shields.io/packagist/dt/d3strukt0r/votifier-client.svg)](https://packagist.org/packages/d3strukt0r/votifier-client)
[![Packagist](https://img.shields.io/packagist/l/d3strukt0r/votifier-client.svg)](https://github.com/D3strukt0r/Votifier-PHP-Client/blob/master/LICENSE)

[![Travis](https://img.shields.io/travis/D3strukt0r/Votifier-PHP-Client.svg)](https://travis-ci.org/D3strukt0r/Votifier-PHP-Client)
[![Coveralls](https://img.shields.io/coveralls/D3strukt0r/Votifier-PHP-Client.svg)](https://coveralls.io/github/D3strukt0r/Votifier-PHP-Client)
[![SensioLabs Insight](https://img.shields.io/sensiolabs/i/7cce6f21-d05b-4b97-9191-080bc88d704d.svg)](https://insight.sensiolabs.com/projects/7cce6f21-d05b-4b97-9191-080bc88d704d)

This php script allows easy using of the bukkit plugin Votifier

## Installation

Add the client using [Composer](http://getcomposer.org/).
```bash
composer require d3strukt0r/votifier-client
```

## Usage

Simply create an object with all informations

(For server with the classic Votifier plugins)
```php
use D3strukt0r\VotifierClient\ServerType\ClassicVotifier;
use D3strukt0r\VotifierClient\Vote;
use D3strukt0r\VotifierClient\VoteType\ClassicVote;

$serverType = new ClassicVotifier('127.0.0.1', null, 'MIIBIjANBgkq...');
$voteType = new ClassicVote($_GET['username'], 'Your vote list', $_SERVER['REMOTE_ADDR']);
$vote = new Vote($voteType, $serverType);
```

(For server which use the NuVotifier plugin (v1 protocol) (HINT: It's EXATCLY the same like method 1))
```php
use D3strukt0r\VotifierClient\ServerType\NuVotifier;
use D3strukt0r\VotifierClient\Vote;
use D3strukt0r\VotifierClient\VoteType\ClassicVote;

$serverType = new NuVotifier('127.0.0.1', null, 'MIIBIjANBgkq...');
$voteType = new ClassicVote($_GET['username'], 'Your vote list', $_SERVER['REMOTE_ADDR']);
$vote = new Vote($voteType, $serverType);
```

(For server which use the NuVotifier plugin with v2 protocol)
```php
use D3strukt0r\VotifierClient\ServerType\NuVotifier;
use D3strukt0r\VotifierClient\Vote;
use D3strukt0r\VotifierClient\VoteType\ClassicVote;

$serverType = new NuVotifier('127.0.0.1', null, 'MIIBIjANBgkq...', true, '7j302r4n...');
$voteType = new ClassicVote($_GET['username'], 'Your vote list', $_SERVER['REMOTE_ADDR']);
$vote = new Vote($voteType, $serverType);
```

and then send it.
```php
try {
    $vote->send();
    // Connection created, and vote sent. Doesn't mean the server handled it correctly, but the client did.
} catch (\Exception $exception) {
    // Could not send Vote. Normally this happens when the client can't create a connection.
}
```

## API

Can be found on https://d3strukt0r.github.io/Votifier-PHP-Client/
