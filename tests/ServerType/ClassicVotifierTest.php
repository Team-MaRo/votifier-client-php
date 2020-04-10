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

/**
 * @internal
 * @coversNothing
 */
final class ClassicVotifierTest extends TestCase
{
    /** @var \D3strukt0r\VotifierClient\ServerType\ClassicVotifier */
    private $obj;

    private $key = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAuyi7TXsufptucSYoVgZLonqFxtYvK0uJoxpExE+hcXRz3tR9jbXxtJ'.
    '.v689/T+CHmvxJmli7g0CL0NucFDAdltat7bYu6AQMtWa7CYgvEtddwR5/ZMkZ1c3swK61fVeIsGE3oaA8Gdz1iBoG5njNmHtPzZm1CRWEYhUMMEP'.
    'u9mBmqTRSYGrDr7NDJ5TL0frpLpPL/4rSTIOCJl0lBzzIT7supRmzppgeuWoh2M2lNUna329xtD5bhRPzmcIh4O2wC3jNQ+yh286mTcLG4AFBQgrS'.
    'GfUHAZa6/l5rmF09Mg5CCvxqj05EBXafYGEH7bojtzDFC3J6NliAkMghk0jmrxQIDAQAB';

    protected function setUp(): void
    {
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
