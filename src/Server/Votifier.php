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

namespace D3strukt0r\VotifierClient\Server;

use D3strukt0r\VotifierClient\Exception\NotVotifierException;
use D3strukt0r\VotifierClient\Exception\Socket\NoConnectionException;
use D3strukt0r\VotifierClient\Exception\Socket\PackageNotReceivedException;
use D3strukt0r\VotifierClient\Exception\Socket\PackageNotSentException;
use D3strukt0r\VotifierClient\Vote\VoteInterface;
use DateTime;

/**
 * The Class to access a server which uses the classic "Votifier" plugin.
 */
class Votifier extends GenericServerType
{
    /**
     * {@inheritdoc}
     *
     * @throws NoConnectionException       If connection couldn't be established
     * @throws NotVotifierException        If the server we are connected to is not a valid Votifier server
     * @throws PackageNotReceivedException If there was an error receiving the package
     * @throws PackageNotSentException     If there was an error sending the package
     */
    public function sendVote(VoteInterface ...$votes): void
    {
        foreach ($votes as $vote) {
            // Connect to the server
            $socket = $this->getSocket();
            $socket->open($this->getHost(), $this->getPort());

            // Check whether the connection really belongs to a Votifier plugin
            if (!$this->verifyConnection($socket->read(64))) {
                throw new NotVotifierException();
            }

            // Update the timestamp of the vote being sent
            $vote->setTimestamp(new DateTime());

            // Send the vote
            $socket->write($this->preparePackage($vote));

            // Make sure to close the connection after package was sent
            $socket->__destruct();
        }
    }

    /**
     * Verifies that the connection is correct.. Read more:
     * https://github.com/vexsoftware/votifier/wiki/Protocol-Documentation.
     *
     * @param string|null $header (Required) The header that the plugin usually sends
     *
     * @return bool returns true if connections is available, otherwise false
     */
    protected function verifyConnection(?string $header): bool
    {
        if (null === $header || false === mb_strpos($header, 'VOTIFIER')) {
            return false;
        }

        return true;
    }

    /**
     * Create encrypted package for default Votifier. Read more:
     * https://github.com/vexsoftware/votifier/wiki/Protocol-Documentation.
     *
     * @param voteInterface $vote The vote package with all the information
     *
     * @return string returns the string to be sent to the server
     */
    protected function preparePackage(VoteInterface $vote): string
    {
        // Details of the vote
        $votePackage = 'VOTE' . "\n" .
            $vote->getServiceName() . "\n" .
            $vote->getUsername() . "\n" .
            $vote->getAddress() . "\n" .
            $vote->getTimestamp() . "\n";

        // Encrypt the string
        openssl_public_encrypt($votePackage, $encryptedVotePackage, $this->getPublicKey(), OPENSSL_SSLV23_PADDING);

        return $encryptedVotePackage;
    }
}
