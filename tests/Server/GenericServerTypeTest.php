<?php

/**
 * Votifier PHP Client
 *
 * @package   Votifier Client
 * @author    Manuele Vaccari <dev@d3strukt0r.dev>
 * @copyright Copyright (c) 2015-2020, 2026 Manuele Vaccari <dev@d3strukt0r.dev>
 * @license   https://github.com/Team-MaRo/votifier-client-php/blob/master/LICENSE.txt MIT License
 * @link      https://github.com/Team-MaRo/votifier-client-php
 */

namespace D3strukt0r\Votifier\Client\Server;

use D3strukt0r\Votifier\Client\Socket;
use D3strukt0r\Votifier\Client\Vote\VoteInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class GenericServerTypeTest.
 *
 * @covers \D3strukt0r\Votifier\Client\Server\GenericServer
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
        $this->object = new class extends GenericServer {
            public function verifyConnection(): void {}

            public function sendVote(VoteInterface ...$votes): void
            {
                if ($votes !== null) {
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
        self::assertInstanceOf('D3strukt0r\Votifier\Client\Server\GenericServer', $this->object);
    }

    public function testSocket(): void
    {
        $socket = new Socket();
        $this->object->setSocket($socket);
        self::assertSame($socket, $this->object->getSocket());
    }

    public function testHost(): void
    {
        $this->object->setHost('mock_host');
        self::assertSame('mock_host', $this->object->getHost());
    }

    public function testPort(): void
    {
        $this->object->setPort(1);
        self::assertSame(1, $this->object->getPort());
    }

    public function testPublicKey(): void
    {
        $key = file_get_contents(__DIR__.\DIRECTORY_SEPARATOR.'votifier_public.key');
        $keyFormatted = wordwrap($key, 64, "\n", true);
        $keyFormatted = <<<EOF
-----BEGIN PUBLIC KEY-----
{$keyFormatted}
-----END PUBLIC KEY-----
EOF;

        $this->object->setPublicKey($key);
        self::assertSame($keyFormatted, $this->object->getPublicKey());
    }
}
