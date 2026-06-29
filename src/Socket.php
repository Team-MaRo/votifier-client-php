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

namespace D3strukt0r\Votifier\Client;

use D3strukt0r\Votifier\Client\Exception\Socket\NoConnectionException;
use D3strukt0r\Votifier\Client\Exception\Socket\PackageNotReceivedException;
use D3strukt0r\Votifier\Client\Exception\Socket\PackageNotSentException;

/**
 * Creates a class for socket functionality.
 *
 * @codeCoverageIgnore
 */
class Socket
{
    /**
     * @var int Seconds to wait for the TCP connection to be established
     */
    private const CONNECT_TIMEOUT = 3;

    /**
     * @var int Seconds to wait for a response (the greeting and the NuVotifier v2
     *          reply). Generous enough that a slow or distant server doesn't trip a
     *          false timeout, but far below PHP's 60s default_socket_timeout, which
     *          previously made an unresponsive endpoint hang for a full minute.
     */
    private const READ_TIMEOUT = 10;

    /**
     * @var resource the connection to the server
     */
    private $socket;

    /**
     * Closes the connection when the object is destroyed.
     */
    public function __destruct()
    {
        if (\is_resource($this->socket)) {
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
        $socket = @fsockopen($host, $port, $errorNumber, $errorString, self::CONNECT_TIMEOUT);
        if ($socket === false) {
            throw new NoConnectionException(\sprintf('Could not connect to %s:%d (%s). Make sure the Votifier port (default 8192, not the Minecraft port 25565) is open and reachable from this host.', $host, $port, $errorString !== '' ? $errorString : 'unknown error'), $errorNumber);
        }
        // Cap the wait for a response; otherwise reads block for php.ini's
        // default_socket_timeout (60s) before failing.
        stream_set_timeout($socket, self::READ_TIMEOUT);
        $this->socket = $socket;
    }

    /**
     * Sends a string to the server.
     *
     * @param string $string The string which should be sent to the server
     *
     * @throws NoConnectionException   If connection has not been set up
     * @throws PackageNotSentException If there was an error sending the package
     */
    public function write(string $string): void
    {
        if (!\is_resource($this->socket)) {
            throw new NoConnectionException();
        }

        if (fwrite($this->socket, $string) === false) {
            throw new PackageNotSentException();
        }
    }

    /**
     * Reads a string which is being received from the server.
     *
     * @param int $length [optional] The length of the requested string
     *
     * @return string returns the string received from the server
     *
     * @throws NoConnectionException       If connection has not been set up
     * @throws PackageNotReceivedException If there was an error receiving the package
     */
    public function read(int $length = 64): string
    {
        if (!\is_resource($this->socket)) {
            throw new NoConnectionException();
        }

        if (!$string = fread($this->socket, $length)) {
            if (stream_get_meta_data($this->socket)['timed_out']) {
                throw new PackageNotReceivedException('The server accepted the connection but did not respond within '.self::READ_TIMEOUT.' seconds. Check that the host and the Votifier port (default 8192, not the Minecraft port 25565) are correct and reachable.');
            }

            throw new PackageNotReceivedException();
        }

        return $string;
    }
}
