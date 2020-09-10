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

use D3strukt0r\VotifierClient\ServerType\ServerTypeInterface;
use Exception;

/**
 * The class ServerConnection is used to create a connection to a server.
 */
class ServerConnection
{
    /**
     * @var ServerTypeInterface the server type information package
     */
    private $serverType;

    /**
     * @var resource the connection to the server
     */
    private $socket;

    /**
     * Creates the ServerConnection object.
     *
     * @param ServerTypeInterface $serverType (Required) The server type information package to connect to
     *
     * @throws Exception
     */
    public function __construct(ServerTypeInterface $serverType)
    {
        $this->serverType = $serverType;
        $socket = fsockopen($serverType->getHost(), $serverType->getPort(), $errorNumber, $errorString, 3);
        if (false === $socket) {
            throw new Exception($errorString, $errorNumber);
        }
        $this->socket = $socket;
    }

    /**
     * Closes the connection when the object is destroyed.
     */
    public function __destruct()
    {
        if ($this->socket) {
            fclose($this->socket);
        }
    }

    /**
     * Sends a string to the server.
     *
     * @param string $string (Required) The string which should be sent to the server
     *
     * @return bool returns true if string was sent, or false if not
     */
    public function send(string $string): bool
    {
        if (!$this->socket) {
            return false;
        }

        if (false === fwrite($this->socket, $string)) {
            $this->__destruct();

            return false;
        }

        return true;
    }

    /**
     * Reads a string which is being received from the server.
     *
     * @param int $length (Optional) The length of the requested string
     *
     * @return string|null returns the string that was received from the server
     */
    public function receive(int $length = 64): ?string
    {
        if (!$this->socket) {
            return null;
        }

        if (!$socket = fread($this->socket, $length)) {
            return null;
        }

        return $socket;
    }
}
