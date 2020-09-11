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

use D3strukt0r\VotifierClient\ServerConnection;
use D3strukt0r\VotifierClient\VoteType\VoteInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class GenericServerTypeTest.
 *
 * @covers \D3strukt0r\VotifierClient\ServerType\GenericServerType
 *
 * @internal
 */
final class GenericServerTypeTest extends TestCase
{
    /**
     * @var GenericServerType The main object
     */
    private $object;

    protected function setUp(): void
    {
        $this->object = new class () extends GenericServerType {
            public function verifyConnection(?string $header): bool
            {
            }

            public function send(ServerConnection $connection, VoteInterface $vote): void
            {
            }
        };
    }

    protected function tearDown(): void
    {
        $this->object = null;
    }

    public function testInstanceOf(): void
    {
        $this->assertInstanceOf('D3strukt0r\VotifierClient\ServerType\GenericServerType', $this->object);
    }

    public function testHost(): void
    {
        $this->object->setHost('mock_host');
        $this->assertSame('mock_host', $this->object->getHost());
    }

    public function testPort(): void
    {
        $this->object->setPort(1);
        $this->assertSame(1, $this->object->getPort());
    }

    public function testPublicKey(): void
    {
        $key = file_get_contents('tests/ServerType/votifier_public.key');
        $keyFormatted = wordwrap($key, 65, "\n", true);
        $keyFormatted = <<<EOF
-----BEGIN PUBLIC KEY-----
{$keyFormatted}
-----END PUBLIC KEY-----
EOF;

        $this->object->setPublicKey($key);
        $this->assertSame($keyFormatted, $this->object->getPublicKey());
    }
}
