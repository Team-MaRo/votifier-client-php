<?php

/**
 * Votifier PHP Client
 *
 * @package   VotifierClient
 * @author    Manuele Vaccari <manuele.vaccari@gmail.com>
 * @copyright Copyright (c) 2017-2019 Manuele Vaccari <manuele.vaccari@gmail.com>
 * @license   https://github.com/D3strukt0r/Votifier-PHP-Client/blob/master/LICENSE.md MIT License
 * @link      https://github.com/D3strukt0r/Votifier-PHP-Client
 */

namespace D3strukt0r\VotifierClient;

use D3strukt0r\VotifierClient\ServerType\ServerTypeInterface;

/**
 * The class ServerConnection is used to create a connection to a server.
 */
class ServerConnection
{
    /**
     * @var ServerTypeInterface The server type information package
     */
    private $serverType;

    /**
     * @var resource The connection to the server
     */
    private $s;

    /**
     * Creates the ServerConnection object.
     *
     * @param ServerTypeInterface $serverType (Required) The server type information package to connect to
     *
     * @throws \Exception
     */
    public function __construct(ServerTypeInterface $serverType)
    {
        $this->serverType = $serverType;
        $s = fsockopen($serverType->getHost(), $serverType->getPort(), $errno, $errstr, 3);
        if (false === $s) {
            throw new \Exception($errstr, $errno);
        }
        $this->s = $s;
    }

    /**
     * Closes the connection when the object is destroyed.
     */
    public function __destruct()
    {
        if ($this->s) {
            fclose($this->s);
        }
    }

    /**
     * Sends a string to the server and return true if it worked or false if not.
     *
     * @param string $string (Required) The string which should be sent to the server
     *
     * @return bool
     */
    public function send(string $string): bool
    {
        if (!$this->s) {
            return false;
        }

        if (false === fwrite($this->s, $string)) {
            $this->__destruct();

            return false;
        }

        return true;
    }

    /**
     * Reads a string which is being received from the server. Returns the string.
     *
     * @param int $length (Optional) The length of the requested string
     *
     * @return string|null
     */
    public function receive(int $length = 64): ?string
    {
        if (!$this->s) {
            return null;
        }

        if (!$s = fread($this->s, $length)) {
            return null;
        }

        return $s;
    }
}
