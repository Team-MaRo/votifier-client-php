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
 * The classic vote package can be used by most plugins.
 */
class ClassicVote implements VoteInterface
{
    /**
     * @var string the name of the list/service
     */
    private $serviceName;

    /**
     * @var string the username who wants to receive the rewards
     */
    private $username;

    /**
     * @var string the IP Address of the user
     */
    private $address;

    /**
     * @var DateTime the time when the vote will be sent
     */
    private $timestamp;

    /**
     * {@inheritdoc}
     */
    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    /**
     * {@inheritdoc}
     */
    public function setServiceName(string $serviceName): self
    {
        $this->serviceName = $serviceName;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public function setUsername(string $username)
    {
        // Replace username to letters, numbers and "_"
        $this->username = preg_replace('/[^A-Za-z0-9_]+/', '', $username);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * {@inheritdoc}
     */
    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTimestamp(): ?int
    {
        return $this->timestamp->getTimestamp();
    }

    /**
     * {@inheritdoc}
     */
    public function setTimestamp(DateTime $timestamp = null): self
    {
        $this->timestamp = $timestamp ?: new DateTime();

        return $this;
    }
}
