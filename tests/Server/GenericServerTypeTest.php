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

use D3strukt0r\VotifierClient\Socket;
use D3strukt0r\VotifierClient\Vote\VoteInterface;
use PHPUnit\Framework\TestCase;

use const DIRECTORY_SEPARATOR;

/**
 * Class GenericServerTypeTest.
 *
 * @covers \D3strukt0r\VotifierClient\Server\GenericServerType
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
                return true;
            }

            public function sendVote(VoteInterface ...$votes): void
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
        $this->assertInstanceOf('D3strukt0r\VotifierClient\Server\GenericServerType', $this->object);
    }

    public function testSocket(): void
    {
        $socket = new Socket();
        $this->object->setSocket($socket);
        $this->assertSame($socket, $this->object->getSocket());
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
        $key = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'votifier_public.key');
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
