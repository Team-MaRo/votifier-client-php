<?php

namespace D3strukt0r\VotifierClient\VoteType;

class ClassicVote implements VoteInterface
{
    private $serviceName;
    private $username;
    private $address;
    private $timestamp;

    /**
     * ClassicVote constructor.
     *
     * @param string         $username
     * @param string         $serviceName
     * @param string         $address
     * @param \DateTime|null $timestamp
     */
    public function __construct($username, $serviceName, $address, \DateTime $timestamp = null)
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
    public function getServiceName()
    {
        return $this->serviceName;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * {@inheritdoc}
     */
    public function getTimestamp()
    {
        if (null !== $this->timestamp) {
            return $this->timestamp->getTimestamp();
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function setTimestamp(\DateTime $timestamp = null)
    {
        $this->timestamp = $timestamp ?: new \DateTime();

        return $this;
    }
}
