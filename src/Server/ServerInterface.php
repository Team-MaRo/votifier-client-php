<?php

/**
 * Votifier PHP Client
 *
 * @package   Votifier Client
 * @author    Manuele Vaccari <dev@d3strukt0r.dev>
 * @copyright Copyright (c) 2015-2020, 2026 Manuele Vaccari <dev@d3strukt0r.dev>
 * @license   https://github.com/Team-MaRo/votifier-client-php/blob/master/LICENSE.txt MIT License
 * @link      https://github.com/Team-MaRo/votifier-client-php
 */

namespace D3strukt0r\Votifier\Client\Server;

use D3strukt0r\Votifier\Client\Exception\NotVotifierException;
use D3strukt0r\Votifier\Client\Exception\Socket\NoConnectionException;
use D3strukt0r\Votifier\Client\Exception\Socket\PackageNotReceivedException;
use D3strukt0r\Votifier\Client\Exception\Socket\PackageNotSentException;
use D3strukt0r\Votifier\Client\Vote\VoteInterface;

/**
 * The interface ServerInterface is used to define a PluginType on the server.
 */
interface ServerInterface
{
    /**
     * Gets the host.
     *
     * @return null|string returns the host
     */
    public function getHost(): ?string;

    /**
     * Sets the host.
     *
     * @param string $host The host
     *
     * @return $this returns the class itself, for doing multiple things at once
     */
    public function setHost(string $host);

    /**
     * Gets the port.
     *
     * @return int returns the port
     */
    public function getPort(): int;

    /**
     * Sets the port.
     *
     * @param int $port The port
     *
     * @return $this returns the class itself, for doing multiple things at once
     */
    public function setPort(int $port);

    /**
     * Gets the public key.
     *
     * @return null|string returns the public key
     */
    public function getPublicKey(): ?string;

    /**
     * Sets the public key.
     *
     * @param string $publicKey The public key
     *
     * @return $this returns the class itself, for doing multiple things at once
     */
    public function setPublicKey(string $publicKey);

    /**
     * Checks if the server actually belongs to Votifier.
     *
     * @throws \InvalidArgumentException   If one required parameter wasn't set
     * @throws NoConnectionException       If connection couldn't be established
     * @throws PackageNotReceivedException If there was an error receiving the package
     * @throws NotVotifierException        If the server we are connected to is not a valid Votifier server
     */
    public function verifyConnection(): void;

    /**
     * Sends the vote packages to the server.
     *
     * @param VoteInterface ...$votes The vote packages
     *
     * @throws \InvalidArgumentException   If one required parameter wasn't set
     * @throws NoConnectionException       If connection couldn't be established
     * @throws PackageNotReceivedException If there was an error receiving the package
     * @throws PackageNotSentException     If there was an error sending the package
     * @throws NotVotifierException        If the server we are connected to is not a valid Votifier server
     */
    public function sendVote(VoteInterface ...$votes): void;
}
