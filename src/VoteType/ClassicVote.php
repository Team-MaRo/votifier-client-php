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

namespace D3strukt0r\VotifierClient\VoteType;

/**
 * The classic vote package can be used by most plugins.
 */
class ClassicVote implements VoteInterface
{
    /**
     * @var string The name of the list/service
     */
    private $serviceName;

    /**
     * @var string The username who wants to receive the rewards
     */
    private $username;

    /**
     * @var string The IP Address of the user
     */
    private $address;

    /**
     * @var \DateTime|null The time when the vote will be sent
     */
    private $timestamp;

    /**
     * Creates the ClassicVote object.
     *
     * @param string         $username    (Required) The username who wants to receive the rewards
     * @param string         $serviceName (Required) The name of the list/service
     * @param string         $address     (Required) The IP Address of the user
     * @param \DateTime|null $timestamp   (Optional) The time when the vote will be sent
     */
    public function __construct(string $username, string $serviceName, string $address, \DateTime $timestamp = null)
    {
        // Replace username to letters, numbers and "_"
        $this->username = preg_replace('/[^A-Za-z0-9_]+/', '', $username);
        $this->serviceName = $serviceName;
        $this->address = $address;
        $this->timestamp = $timestamp;
    }

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
    public function getUsername(): string
    {
        return $this->username;
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
    public function getTimestamp(): ?int
    {
        if (null !== $this->timestamp) {
            return $this->timestamp->getTimestamp();
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function setTimestamp(\DateTime $timestamp = null): self
    {
        $this->timestamp = $timestamp ?: new \DateTime();

        return $this;
    }
}
