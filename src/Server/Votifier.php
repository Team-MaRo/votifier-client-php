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

namespace D3strukt0r\Votifier\Client\Server;

use D3strukt0r\Votifier\Client\Exception\NotVotifierException;
use D3strukt0r\Votifier\Client\Exception\Socket\NoConnectionException;
use D3strukt0r\Votifier\Client\Exception\Socket\PackageNotReceivedException;
use D3strukt0r\Votifier\Client\Exception\Socket\PackageNotSentException;
use D3strukt0r\Votifier\Client\Vote\VoteInterface;
use DateTime;
use InvalidArgumentException;

/**
 * The Class to access a server which uses the classic "Votifier" plugin.
 */
class Votifier extends GenericServer
{
    /**
     * {@inheritdoc}
     *
     * @throws InvalidArgumentException    If one required parameter wasn't set
     * @throws NoConnectionException       If connection couldn't be established
     * @throws PackageNotReceivedException If there was an error receiving the package
     * @throws PackageNotSentException     If there was an error sending the package
     * @throws NotVotifierException        If the server we are connected to is not a valid Votifier server
     */
    public function sendVote(VoteInterface ...$votes): void
    {
        // Check if all variables have been set, to create a connection
        $this->checkRequiredVariablesForSocket();

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

            // Check if all variables have been set, to create a package
            $this->checkRequiredVariablesForPackage($vote);

            // Send the vote
            $socket->write($this->preparePackage($vote));

            // Make sure to close the connection after package was sent
            $socket->__destruct();
        }
    }

    /**
     * Check that both host and port have been set.
     *
     * @throws InvalidArgumentException If one required parameter wasn't set
     */
    protected function checkRequiredVariablesForSocket(): void
    {
        if (!isset($this->host, $this->port)) {
            // $countError = 0;
            $errorMessage = '';

            if (null === $this->host) {
                $errorMessage .= 'The host variable wasn\'t set with "->setHost(...)".';
                // ++$countError;
            }
            // Not needed, as port has a default value
            // if (null === $this->port) {
            //     $errorMessage .= $countError > 0 ? ' ' : '';
            //     $errorMessage .= 'The port variable wasn\'t set with "->setPort(...)".';
            // }

            throw new InvalidArgumentException($errorMessage);
        }
    }

    /**
     * Check that service name, username, address, timestamp and public key have been set.
     *
     * @param VoteInterface $vote The vote to check
     *
     * @throws InvalidArgumentException If one required parameter wasn't set
     */
    protected function checkRequiredVariablesForPackage(VoteInterface $vote)
    {
        if (
            null === $vote->getServiceName()
            || null === $vote->getUsername()
            || null === $vote->getAddress()
            || null === $vote->getTimestamp()
            || !isset($this->publicKey)
        ) {
            $countError = 0;
            $errorMessage = '';

            if (null === $vote->getServiceName()) {
                $errorMessage .= 'The host variable wasn\'t set with "->setServiceName(...)".';
                ++$countError;
            }
            if (null === $vote->getUsername()) {
                $errorMessage .= $countError > 0 ? ' ' : '';
                $errorMessage .= 'The host variable wasn\'t set with "->setUsername(...)".';
                ++$countError;
            }
            if (null === $vote->getAddress()) {
                $errorMessage .= $countError > 0 ? ' ' : '';
                $errorMessage .= 'The host variable wasn\'t set with "->setAddress(...)".';
                ++$countError;
            }
            if (null === $vote->getTimestamp()) {
                $errorMessage .= $countError > 0 ? ' ' : '';
                $errorMessage .= 'The host variable wasn\'t set with "->setTimestamp(...)".';
            }
            if (!isset($this->publicKey)) {
                $errorMessage .= $countError > 0 ? ' ' : '';
                $errorMessage .= 'The public key variable wasn\'t set with "->setPublicKey(...)".';
            }

            throw new InvalidArgumentException($errorMessage);
        }
    }

    /**
     * Verifies that the connection is correct. Read more:
     * https://github.com/vexsoftware/votifier/wiki/Protocol-Documentation.
     *
     * @param string|null $header The header that the plugin usually sends
     *
     * @return bool returns true if connections is available, otherwise false
     */
    protected function verifyConnection(?string $header): bool
    {
        if (
            null === $header
            || false === mb_strpos($header, 'VOTIFIER')
        ) {
            return false;
        }

        return true;
    }

    /**
     * Create encrypted package for default Votifier. Read more:
     * https://github.com/vexsoftware/votifier/wiki/Protocol-Documentation.
     *
     * @param VoteInterface $vote The vote package with all the information
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
