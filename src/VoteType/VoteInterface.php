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

namespace D3strukt0r\VotifierClient\VoteType;

/**
 * The interface VoteInterface will be used for different kinds of vote packages.
 */
interface VoteInterface
{
    /**
     * The name of the list/service.
     *
     * @return string
     */
    public function getServiceName();

    /**
     * The username who wants to receive the rewards.
     *
     * @return string
     */
    public function getUsername();

    /**
     * The IP Address of the user.
     *
     * @return string
     */
    public function getAddress();

    /**
     * Get the time when the vote was sent.
     *
     * @return int|null
     */
    public function getTimestamp();

    /**
     * Set the time when the vote will be sent.
     *
     * @param \DateTime|null $timestamp (Optional) Either give a wanted timestamp or it will use the current time
     *
     * @return $this
     */
    public function setTimestamp(\DateTime $timestamp = null);
}
