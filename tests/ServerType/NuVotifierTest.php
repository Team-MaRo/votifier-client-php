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
final class NuVotifierTest extends TestCase
{
    /** @var \D3strukt0r\VotifierClient\ServerType\NuVotifier */
    private $obj;
    /** @var \D3strukt0r\VotifierClient\ServerType\NuVotifier */
    private $obj2;

    private $key = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAuyi7TXsufptucSYoVgZLonqFxtYvK0uJoxpExE+hcXRz3tR9jbXxtJ'.
    'v689/T+CHmvxJmli7g0CL0NucFDAdltat7bYu6AQMtWa7CYgvEtddwR5/ZMkZ1c3swK61fVeIsGE3oaA8Gdz1iBoG5njNmHtPzZm1CRWEYhUMMEPu'.
    '9mBmqTRSYGrDr7NDJ5TL0frpLpPL/4rSTIOCJl0lBzzIT7supRmzppgeuWoh2M2lNUna329xtD5bhRPzmcIh4O2wC3jNQ+yh286mTcLG4AFBQgrSG'.
    'fUHAZa6/l5rmF09Mg5CCvxqj05EBXafYGEH7bojtzDFC3J6NliAkMghk0jmrxQIDAQAB';

    protected function setUp(): void
    {
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
        $testResultV2 = '{"signature":"LTsZweI\/1UwR+PHV9OKK0ULJRw2Ilavh17A8b6C0LBw=","payload":"{\"username\":\"mock_'.
                        'user\",\"serviceName\":\"mock_service\",\"timestamp\":null,\"address\":\"mock_address\",\"cha'.
                        'llenge\":\"mock_challenge\"}"}';
        static::assertStringEndsWith($testResultV2, $string);
    }
}
