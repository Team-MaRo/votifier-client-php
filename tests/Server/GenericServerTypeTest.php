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

use D3strukt0r\Votifier\Client\Socket;
use D3strukt0r\Votifier\Client\Vote\VoteInterface;
use PHPUnit\Framework\TestCase;

use const DIRECTORY_SEPARATOR;

/**
 * Class GenericServerTypeTest.
 *
 * @covers \D3strukt0r\Votifier\Client\Server\GenericServer
 *
 * @internal
 */
final class GenericServerTypeTest extends TestCase
{
    /**
     * @var GenericServer The main object
     */
    private $object;

    protected function setUp(): void
    {
        $this->object = new class () extends GenericServer {
            public function verifyConnection(): void
            {
            }

            public function sendVote(VoteInterface ...$votes): void
            {
                if (null !== $votes) {
                    return;
                }
            }
        };
    }

    protected function tearDown(): void
    {
        $this->object = null;
    }

    public function testInstanceOf(): void
    {
        $this->assertInstanceOf('D3strukt0r\Votifier\Client\Server\GenericServer', $this->object);
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
