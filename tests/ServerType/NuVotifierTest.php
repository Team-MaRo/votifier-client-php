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

use D3strukt0r\VotifierClient\VoteType\ClassicVote;
use PHPUnit\Framework\TestCase;

use function file_get_contents;

/**
 * Class NuVotifierTest.
 *
 * @covers \D3strukt0r\VotifierClient\ServerType\NuVotifier
 *
 * @internal
 */
final class NuVotifierTest extends TestCase
{
    /** @var NuVotifier */
    private $obj;
    /** @var NuVotifier */
    private $obj2;

    /**
     * An example public key.
     *
     * @var string
     */
    private $key;

    protected function setUp(): void
    {
        $this->key = file_get_contents('tests/ServerType/votifier_public.key');
        $this->obj = new NuVotifier('mock_host', 00000, $this->key);
        $this->obj2 = new NuVotifier('mock_host', 00000, null, true, 'mock_token');
    }

    protected function tearDown(): void
    {
        $this->obj = null;
        $this->obj2 = null;
    }

    public function testInstanceOf(): void
    {
        static::assertInstanceOf('D3strukt0r\VotifierClient\ServerType\NuVotifier', $this->obj);
    }

    public function testValues(): void
    {
        static::assertSame('mock_host', $this->obj->getHost());
        static::assertSame(00000, $this->obj->getPort());
        $key = wordwrap($this->key, 65, "\n", true);
        $key = <<<EOF
-----BEGIN PUBLIC KEY-----
{$key}
-----END PUBLIC KEY-----
EOF;
        static::assertSame($key, $this->obj->getPublicKey());

        static::assertTrue($this->obj2->isProtocolV2());
    }

    public function testHeaderVerification(): void
    {
        static::assertFalse($this->obj->verifyConnection(false));
        static::assertFalse($this->obj->verifyConnection('VOTIF'));
        static::assertTrue($this->obj->verifyConnection('VOTIFIER 2.3.7 SOMETHING'));
    }

    public function testPackagePreparationV2(): void
    {
        $testVote = new ClassicVote('mock_user', 'mock_service', 'mock_address');
        $string = $this->obj->preparePackageV2($testVote, 'mock_challenge');
        static::assertStringStartsWith('s:', $string);
        $testResultV2 = '{' .
            '"signature":"LTsZweI\/1UwR+PHV9OKK0ULJRw2Ilavh17A8b6C0LBw=",' .
            '"payload":"{' .
                '\"username\":\"mock_user\",' .
                '\"serviceName\":\"mock_service\",' .
                '\"timestamp\":null,' .
                '\"address\":\"mock_address\",' .
                '\"challenge\":\"mock_challenge\"' .
            '}"' .
        '}';
        static::assertStringEndsWith($testResultV2, $string);
    }
}
