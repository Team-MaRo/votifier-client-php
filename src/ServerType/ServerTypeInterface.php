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

namespace D3strukt0r\VotifierClient\ServerType;

use D3strukt0r\VotifierClient\ServerConnection;
use D3strukt0r\VotifierClient\VoteType\VoteInterface;
use Exception;

/**
 * The interface ServerTypeInterface is used to define a PluginType on the server.
 */
interface ServerTypeInterface
{
    /**
     * Returns the host.
     *
     * @return string returns the host
     */
    public function getHost(): string;

    /**
     * Returns the port.
     *
     * @return int returns the port
     */
    public function getPort(): int;

    /**
     * Returns the public key.
     *
     * @return string returns the public key
     */
    public function getPublicKey(): string;

    /**
     * Verifies that the connection is correct.
     *
     * @param string|null $header (Required) The header that the plugin usually sends
     *
     * @return bool returns true if connections is available, otherwise false
     */
    public function verifyConnection(?string $header): bool;

    /**
     * Sends the vote package to the server.
     *
     * @param serverConnection $connection (Required) The connection type to the plugin
     * @param VoteInterface    $vote       (Required) The vote type package
     *
     * @throws Exception
     */
    public function send(ServerConnection $connection, VoteInterface $vote): void;
}
