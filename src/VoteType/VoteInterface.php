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

namespace D3strukt0r\VotifierClient\VoteType;

use DateTime;

/**
 * The interface VoteInterface will be used for different kinds of vote packages.
 */
interface VoteInterface
{
    /**
     * The name of the list/service.
     *
     * @return string returns the name of the list/service
     */
    public function getServiceName(): string;

    /**
     * The username who wants to receive the rewards.
     *
     * @return string returns the username who wants to receive the rewards
     */
    public function getUsername(): string;

    /**
     * The IP Address of the user.
     *
     * @return string returns the IP Address of the user
     */
    public function getAddress(): string;

    /**
     * Get the time when the vote was sent.
     *
     * @return int|null returns the time when the vote was sent
     */
    public function getTimestamp(): ?int;

    /**
     * Set the time when the vote will be sent.
     *
     * @param \DateTime|null $timestamp (Optional) Either give a wanted timestamp or it will use the current time
     *
     * @return self returns the class itself, for doing multiple things at once
     */
    public function setTimestamp(DateTime $timestamp = null);
}
