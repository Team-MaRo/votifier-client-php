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
use D3strukt0r\Votifier\Client\Vote\VoteInterface;

/**
 * The Class to access a server which uses the classic "Votifier" plugin.
 */
class Votifier extends GenericServer
{
    /**
     * {@inheritdoc}
     */
    public function verifyConnection(): void
    {
        // Check if all variables have been set, to create a connection
        $this->checkVariablesForSocket();

        // Connect to the server
        $socket = $this->getSocket();
        $socket->open($this->getHost(), $this->getPort());

        // Check whether the connection really belongs to a Votifier plugin
        if (!$this->verifyConnectionHeader($socket->read(64))) {
            throw new NotVotifierException();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function sendVote(VoteInterface ...$votes): void
    {
        // Check if all variables have been set, to create a connection
        $this->checkVariablesForSocket();

        foreach ($votes as $vote) {
            // Connect to the server
            $socket = $this->getSocket();
            $socket->open($this->getHost(), $this->getPort());

            // Check whether the connection really belongs to a Votifier plugin
            if (!$this->verifyConnectionHeader($socket->read(64))) {
                throw new NotVotifierException();
            }

            // Update the timestamp of the vote being sent
            $vote->setTimestamp(new \DateTime());

            // Check if all variables have been set, to create a package
            $this->checkVariablesForPackage($vote);

            // Send the vote
            $socket->write($this->preparePackage($vote));

            // Make sure to close the connection after package was sent
            $socket->__destruct();
        }
    }

    /**
     * Check that both host and port have been set.
     *
     * @throws \InvalidArgumentException If one required parameter wasn't set
     */
    protected function checkVariablesForSocket(): void
    {
        if (!isset($this->host, $this->port)) {
            // $countError = 0;
            $errorMessage = '';

            if ($this->host === null) {
                $errorMessage .= 'The host variable wasn\'t set with "->setHost(...)".';
                // ++$countError;
            }
            // Not needed, as port has a default value
            // if (null === $this->port) {
            //     $errorMessage .= $countError > 0 ? ' ' : '';
            //     $errorMessage .= 'The port variable wasn\'t set with "->setPort(...)".';
            // }

            throw new \InvalidArgumentException($errorMessage);
        }
    }

    /**
     * Check that service name, username, address, timestamp and public key have been set.
     *
     * @param VoteInterface $vote The vote to check
     *
     * @throws \InvalidArgumentException If one required parameter wasn't set
     */
    protected function checkVariablesForPackage(VoteInterface $vote): void
    {
        if (
            $vote->getServiceName() === null
            || $vote->getUsername() === null
            || $vote->getAddress() === null
            || $vote->getTimestamp() === null
            || !isset($this->publicKey)
        ) {
            $countError = 0;
            $errorMessage = '';

            if ($vote->getServiceName() === null) {
                $errorMessage .= 'The host variable wasn\'t set with "->setServiceName(...)".';
                ++$countError;
            }
            if ($vote->getUsername() === null) {
                $errorMessage .= $countError > 0 ? ' ' : '';
                $errorMessage .= 'The host variable wasn\'t set with "->setUsername(...)".';
                ++$countError;
            }
            if ($vote->getAddress() === null) {
                $errorMessage .= $countError > 0 ? ' ' : '';
                $errorMessage .= 'The host variable wasn\'t set with "->setAddress(...)".';
                ++$countError;
            }
            if ($vote->getTimestamp() === null) {
                $errorMessage .= $countError > 0 ? ' ' : '';
                $errorMessage .= 'The host variable wasn\'t set with "->setTimestamp(...)".';
            }
            if (!isset($this->publicKey)) {
                $errorMessage .= $countError > 0 ? ' ' : '';
                $errorMessage .= 'The public key variable wasn\'t set with "->setPublicKey(...)".';
            }

            throw new \InvalidArgumentException($errorMessage);
        }
    }

    /**
     * Verifies that the connection is correct. Read more:
     * https://github.com/vexsoftware/votifier/wiki/Protocol-Documentation.
     *
     * @param null|string $header The header that the plugin usually sends
     *
     * @return bool returns true if connections is available, otherwise false
     */
    protected function verifyConnectionHeader(?string $header): bool
    {
        if (
            $header === null
            || mb_strpos($header, 'VOTIFIER') === false
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
        $votePackage = 'VOTE'."\n"
            .$vote->getServiceName()."\n"
            .$vote->getUsername()."\n"
            .$vote->getAddress()."\n"
            .$vote->getTimestamp()."\n";

        // Parse the PEM public key up front so an invalid key fails loudly here
        // instead of openssl_public_encrypt() silently producing an empty packet.
        $publicKey = openssl_pkey_get_public($this->getPublicKey());
        if ($publicKey === false) {
            throw new \InvalidArgumentException('The public key set with "->setPublicKey(...)" is not a valid RSA public key.');
        }

        // Encrypt with openssl_public_encrypt's default PKCS#1 v1.5 padding, which is
        // what Votifier decrypts with (Cipher.getInstance("RSA") resolves to PKCS#1
        // v1.5 via the JDK's default SunJCE provider). The old explicit
        // OPENSSL_SSLV23_PADDING was removed in OpenSSL 3.0 and is undefined on PHP 8.x.
        openssl_public_encrypt($votePackage, $encryptedVotePackage, $publicKey);

        return $encryptedVotePackage;
    }
}
