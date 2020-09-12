<?php

/**
 * Votifier PHP Client
 *
 * @package   VotifierClient
 * @author    Manuele Vaccari <manuele.vaccari@gmail.com>
 * @copyright Copyright (c) 2017-2020 Manuele Vaccari <manuele.vaccari@gmail.com>
 * @license   https://github.com/D3strukt0r/votifier-client-php/blob/master/LICENSE.txt GNU General Public License v3.0
 * @link      https://github.com/D3strukt0r/votifier-client-php
 */

namespace D3strukt0r\VotifierClient;

use D3strukt0r\VotifierClient\Exception\Socket\NoConnectionException;
use D3strukt0r\VotifierClient\Exception\Socket\PackageNotReceivedException;
use D3strukt0r\VotifierClient\Exception\Socket\PackageNotSentException;

/**
 * Creates a class for socket functionality.
 *
 * @codeCoverageIgnore
 */
class Socket
{
    /**
     * @var resource the connection to the server
     */
    private $socket;

    /**
     * Closes the connection when the object is destroyed.
     */
    public function __destruct()
    {
        if (is_resource($this->socket)) {
            fclose($this->socket);
        }
    }

    /**
     * Creates a socket connection.
     *
     * @param string $host The hostname or IP address
     * @param int    $port The port of Votifier
     *
     * @throws NoConnectionException If connection couldn't be established
     */
    public function open(string $host, int $port): void
    {
        $socket = @fsockopen($host, $port, $errorNumber, $errorString, 3);
        if (false === $socket) {
            throw new NoConnectionException($errorString, $errorNumber);
        }
        $this->socket = $socket;
    }

    /**
     * Sends a string to the server.
     *
     * @param string $string The string to send
     *
     * @throws NoConnectionException   If connection has not been set up
     * @throws PackageNotSentException If there was an error sending the package
     */
    public function write(string $string): void
    {
        if (!is_resource($this->socket)) {
            throw new NoConnectionException();
        }

        if (false === fwrite($this->socket, $string)) {
            throw new PackageNotSentException();
        }
    }

    /**
     * Gets a string from the server.
     *
     * @param int $length The length of the string to be received
     *
     * @throws NoConnectionException       If connection has not been set up
     * @throws PackageNotReceivedException If there was an error receiving the package
     *
     * @return string returns the string received from the server
     */
    public function read(int $length = 64): string
    {
        if (!is_resource($this->socket)) {
            throw new NoConnectionException();
        }

        if (!$string = fread($this->socket, $length)) {
            throw new PackageNotReceivedException();
        }

        return $string;
    }
}
