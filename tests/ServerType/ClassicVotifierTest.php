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

use PHPUnit\Framework\TestCase;

use function file_get_contents;

/**
 * Class ClassicVotifierTest.
 *
 * @covers \D3strukt0r\VotifierClient\ServerType\ClassicVotifier
 *
 * @internal
 */
final class ClassicVotifierTest extends TestCase
{
    /** @var ClassicVotifier */
    private $obj;

    /**
     * An example public key.
     *
     * @var string
     */
    private $key;

    protected function setUp(): void
    {
        $this->key = file_get_contents('tests/ServerType/votifier_public.key');
        $this->obj = new ClassicVotifier('mock_host', 00000, $this->key);
    }

    protected function tearDown(): void
    {
        $this->obj = null;
    }

    public function testInstanceOf(): void
    {
        static::assertInstanceOf('D3strukt0r\VotifierClient\ServerType\ClassicVotifier', $this->obj);
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
    }

    public function testHeaderVerification(): void
    {
        static::assertFalse($this->obj->verifyConnection(false));
        static::assertFalse($this->obj->verifyConnection('VOTFI'));
        static::assertTrue($this->obj->verifyConnection('VOTIFIER'));
    }

    /*public function testPackagePreparation()
    {
        $string = $this->obj->preparePackage(new ClassicVote('mock_user', 'mock_service', 'mock_address'));
        // Cannot test openssl_encrypt
    }*/
}
