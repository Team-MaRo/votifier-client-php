<?php

namespace D3strukt0r\VotifierClient\ServerType;

use D3strukt0r\VotifierClient\Messages;
use D3strukt0r\VotifierClient\ServerConnection;
use D3strukt0r\VotifierClient\VoteType\VoteInterface;

class ClassicVotifier implements ServerTypeInterface
{
    private $host;
    private $port = 8192;
    private $publicKey;

    /**
     * ClassicVotifier constructor.
     *
     * @param string   $host
     * @param int|null $port
     * @param string   $publicKey
     */
    public function __construct($host, $port, $publicKey)
    {
        $this->host = $host;

        if (null !== $port) {
            $this->port = $port;
        }

        $this->publicKey = $publicKey;
        $this->publicKey = wordwrap($this->publicKey, 65, "\n", true);
        $this->publicKey = <<<EOF
-----BEGIN PUBLIC KEY-----
$this->publicKey
-----END PUBLIC KEY-----
EOF;
    }

    /**
     * {@inheritdoc}
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * {@inheritdoc}
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * {@inheritdoc}
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * {@inheritdoc}
     */
    public function verifyConnection($header)
    {
        if (false === $header || false === mb_strpos($header, 'VOTIFIER')) {
            return false;
        }

        return true;
    }

    /**
     * Create encrypted package for default Votifier.
     *
     * @param \D3strukt0r\VotifierClient\VoteType\VoteInterface $vote
     *
     * @return string
     */
    public function preparePackage(VoteInterface $vote)
    {
        // Details of the vote
        $votePackage = 'VOTE'."\n".
            $vote->getServiceName()."\n".
            $vote->getUsername()."\n".
            $vote->getAddress()."\n".
            $vote->getTimestamp()."\n";

        // Encrypt the string
        openssl_public_encrypt($votePackage, $encryptedVotePackage, $this->getPublicKey());

        return $encryptedVotePackage;
    }

    /**
     * {@inheritdoc}
     */
    public function send(ServerConnection $connection, VoteInterface $vote)
    {
        if (!$this->verifyConnection($connection->receive(64))) {
            throw new \Exception(Messages::get(Messages::NOT_VOTIFIER));
        }

        if (!$connection->send($this->preparePackage($vote))) {
            throw new \Exception(Messages::get(Messages::NOT_SENT_PACKAGE));
        }
    }
}
