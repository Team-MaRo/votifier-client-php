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

Simply create an object with all informations and then send it.
```php
use Votifier\Client\Vote;

$vote = new Vote(
    '127.0.0.1',
    8192,
    'MIIBIjANBgkqhkiG9w0BAQEFAA....',
    $_POST['username'],
    'My own list',
    $_SERVER['REMOTE_ADDR']
);

try {
    $vote->send();
    // Connection created, and vote sent. Doesn't mean the server handled it correctly, but the client did.
} catch (\Exception $exception) {
    // Could not send Vote. Normally this happens when the client can't create a connection.
}
```

## API

You can use these function:
  * **__contruct($server_ip, $votifier_port, $public_key, $username, $server_list, $user_ip)**, initialized the object with the required informations
  * **send()**, creates socket to the server and sends the vote package
