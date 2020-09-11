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

use D3strukt0r\VotifierClient\Exception\NotVotifierException;
use D3strukt0r\VotifierClient\Exception\PackageNotSentException;
use D3strukt0r\VotifierClient\ServerConnection;
use D3strukt0r\VotifierClient\VoteType\VoteInterface;

/**
 * The Class to access a server which uses the classic "Votifier" plugin.
 */
class ClassicVotifier extends GenericServerType
{
    /**
     * {@inheritdoc}
     *
     * @throws NotVotifierException    If the connection response receive didn't start with VOTIFIER...
     * @throws PackageNotSentException When the package couldn't be sent
     */
    public function send(ServerConnection $connection, VoteInterface $vote): void
    {
        if (!$this->verifyConnection($connection->receive(64))) {
            throw new NotVotifierException();
        }

        if (!$connection->send($this->preparePackage($vote))) {
            throw new PackageNotSentException();
        }
    }

    /**
     * Verifies that the connection is correct.
     *
     * @param string|null $header (Required) The header that the plugin usually sends
     *
     * @return bool returns true if connections is available, otherwise false
     */
    private function verifyConnection(?string $header): bool
    {
        if (null === $header || false === mb_strpos($header, 'VOTIFIER')) {
            return false;
        }

        return true;
    }

    /**
     * Create encrypted package for default Votifier.
     *
     * @param voteInterface $vote (Required) The vote package with all the information
     *
     * @return string returns the string to be sent to the server
     */
    private function preparePackage(VoteInterface $vote): string
    {
        // Details of the vote
        $votePackage = 'VOTE' . "\n" .
            $vote->getServiceName() . "\n" .
            $vote->getUsername() . "\n" .
            $vote->getAddress() . "\n" .
            $vote->getTimestamp() . "\n";

        // Encrypt the string
        openssl_public_encrypt($votePackage, $encryptedVotePackage, $this->getPublicKey());

        return $encryptedVotePackage;
    }
}
