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
use D3strukt0r\VotifierClient\Exception\NuVotifierChallengeInvalidException;
use D3strukt0r\VotifierClient\Exception\NuVotifierException;
use D3strukt0r\VotifierClient\Exception\NuVotifierSignatureInvalidException;
use D3strukt0r\VotifierClient\Exception\NuVotifierUnknownServiceException;
use D3strukt0r\VotifierClient\Exception\NuVotifierUsernameTooLongException;
use D3strukt0r\VotifierClient\Exception\PackageNotReceivedException;
use D3strukt0r\VotifierClient\Exception\PackageNotSentException;
use D3strukt0r\VotifierClient\ServerConnection;
use D3strukt0r\VotifierClient\VoteType\VoteInterface;

use function count;

/**
 * The Class to access a server which uses the plugin "NuVotifier".
 */
class NuVotifier extends ClassicVotifier
{
    /**
     * @var bool use version 2 of the protocol
     */
    private $protocolV2 = false;

    /**
     * @var string|null The token from the config.yml.
     */
    private $token;

    /**
     * Checks whether the connection uses the version 2 protocol.
     *
     * @return bool returns true, if using the new version of NuVotifier or false otherwise
     */
    public function isProtocolV2(): bool
    {
        return $this->protocolV2;
    }

    /**
     * Sets whether to use version 2 of the protocol.
     *
     * @param bool $isProtocolV2 Whether to use version 2 of the protocol
     *
     * @return $this returns the class itself, for doing multiple things at once
     */
    public function setProtocolV2(bool $isProtocolV2): self
    {
        $this->protocolV2 = $isProtocolV2;

        return $this;
    }

    /**
     * Gets the token from the config.yml.
     *
     * @return string|null returns The token from the config.yml.
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * Sets the token from the config.yml.
     *
     * @param string|null $token The token from the config.yml.
     *
     * @return $this returns the class itself, for doing multiple things at once
     */
    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @throws NotVotifierException
     * @throws PackageNotSentException
     * @throws PackageNotReceivedException
     * @throws NuVotifierServerErrorException
     */
    public function send(ServerConnection $connection, VoteInterface $vote): void
    {
        if (!$this->isProtocolV2()) {
            parent::send($connection, $vote);

            return;
        }

        if (!$this->verifyConnection($header = $connection->receive(64))) {
            throw new NotVotifierException();
        }
        $header_parts = explode(' ', $header);
        $challenge = mb_substr($header_parts[2], 0, -1);

        if (false === $connection->send($this->preparePackageV2($vote, $challenge))) {
            throw new PackageNotSentException();
        }

        if (!$response = $connection->receive(256)) {
            throw new PackageNotReceivedException();
        }

        /*
         * https://github.com/NuVotifier/NuVotifier/blob/master/common/src/main/java/com/vexsoftware/votifier/net/protocol/VotifierProtocol2Decoder.java
         * Examples:
         * {"status":"ok"}
         * {"status":"error","cause":"CorruptedFrameException","error":"Challenge is not valid"}
         * {"status":"error","cause":"CorruptedFrameException","error":"Unknown service 'xxx'"}
         * {"status":"error","cause":"CorruptedFrameException","error":"Signature is not valid (invalid token?)"}
         * {"status":"error","cause":"CorruptedFrameException","error":"Username too long"} (over 16 characters)
         */
        $result = json_decode($response);
        if ('ok' !== $result->status) {
            if ('Challenge is not valid' === $result->error) {
                throw new NuVotifierChallengeInvalidException();
            } elseif (preg_match('/Unknown service \'(.*)\'/', $result->error, $matches)) {
                throw new NuVotifierUnknownServiceException();
            } elseif ('Signature is not valid (invalid token?)' === $result->error) {
                throw new NuVotifierSignatureInvalidException();
            } elseif ('Username too long' === $result->error) {
                throw new NuVotifierUsernameTooLongException();
            }
            throw new NuVotifierException('Unknown NuVotifier Exception');
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
        $header_parts = explode(' ', $header);
        if (null === $header || false === mb_strpos($header, 'VOTIFIER') || 3 !== count($header_parts)) {
            return false;
        }

        return true;
    }

    /**
     * Prepares the vote package to be sent as version 2 protocol package.
     *
     * @param VoteInterface $vote      (Required) The vote package with information
     * @param string        $challenge (Required) The challenge sent by the server
     *
     * @return string returns the string to be sent to the server
     */
    private function preparePackageV2(VoteInterface $vote, string $challenge): string
    {
        $payloadJson = json_encode(
            [
                'username' => $vote->getUsername(),
                'serviceName' => $vote->getServiceName(),
                'timestamp' => $vote->getTimestamp(),
                'address' => $vote->getAddress(),
                'challenge' => $challenge,
            ]
        );
        $signature = base64_encode(hash_hmac('sha256', $payloadJson, $this->token, true));
        $messageJson = json_encode(['signature' => $signature, 'payload' => $payloadJson]);

        return pack('nn', 0x733a, mb_strlen($messageJson)) . $messageJson;
    }
}
