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

namespace D3strukt0r\Votifier\Client\Vote;

/**
 * The interface VoteInterface will be used for different kinds of vote packages.
 */
interface VoteInterface
{
    /**
     * Gets the name of the list/service.
     *
     * @return null|string returns the name of the list/service
     */
    public function getServiceName(): ?string;

    /**
     * Sets the name of the list/service.
     *
     * @param string $serviceName The name of the list/service
     *
     * @return $this returns the class itself, for doing multiple things at once
     */
    public function setServiceName(string $serviceName);

    /**
     * Gets the username of the user who wants to receive the rewards.
     *
     * @return null|string returns the username who wants to receive the rewards
     */
    public function getUsername(): ?string;

    /**
     * Sets the username of the user who wants to receive the rewards.
     *
     * @param string $username The username of the user who wants to receive the rewards
     *
     * @return $this returns the class itself, for doing multiple things at once
     */
    public function setUsername(string $username);

    /**
     * Gets the IP Address of the user.
     *
     * @return null|string returns the IP Address of the user
     */
    public function getAddress(): ?string;

    /**
     * Sets the IP Address of the user.
     *
     * @param string $address The IP address the user is sending a request from
     *
     * @return $this returns the class itself, for doing multiple things at once
     */
    public function setAddress(string $address);

    /**
     * Gets the time when the vote was sent.
     *
     * @return null|int returns the time when the vote was sent, null otherwise
     */
    public function getTimestamp(): ?int;

    /**
     * Sets the time when the vote will be sent.
     *
     * @param null|\DateTime $timestamp [optional] Either give a wanted timestamp or it will use the current time
     *
     * @return $this returns the class itself, for doing multiple things at once
     */
    public function setTimestamp(?\DateTime $timestamp = null);
}
