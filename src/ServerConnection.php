<?php

/**
 * Votifier PHP Client
 *
 * @package   VotifierClient
 *
 * @author    Manuele Vaccari <manuele.vaccari@gmail.com>
 * @copyright Copyright (c) 2017-2018 Manuele Vaccari <manuele.vaccari@gmail.com>
 * @license   https://github.com/D3strukt0r/Votifier-PHP-Client/blob/master/LICENSE.md MIT License
 *
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
        $this->s = fsockopen($serverType->getHost(), $serverType->getPort(), $errno, $errstr, 3);
        if (!$this->s) {
            $this->__destruct();
            throw new \Exception($errstr, $errno);
        }
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
    public function send($string)
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
     * @return bool|string
     */
    public function receive($length = 64)
    {
        if (!$this->s) {
            return false;
        }

        return fread($this->s, $length);
    }
}
