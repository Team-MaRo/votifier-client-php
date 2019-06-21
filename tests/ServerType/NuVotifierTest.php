<?php

/**
 * Votifier PHP Client
 *
 * @package   VotifierClient
 * @author    Manuele Vaccari <manuele.vaccari@gmail.com>
 * @copyright Copyright (c) 2017-2019 Manuele Vaccari <manuele.vaccari@gmail.com>
 * @license   https://github.com/D3strukt0r/Votifier-PHP-Client/blob/master/LICENSE.md MIT License
 * @link      https://github.com/D3strukt0r/Votifier-PHP-Client
 */

namespace D3strukt0r\VotifierClient\ServerType;

use D3strukt0r\VotifierClient\VoteType\ClassicVote;
use PHPUnit\Framework\TestCase;

class NuVotifierTest extends TestCase
{
    /** @var \D3strukt0r\VotifierClient\ServerType\NuVotifier */
    private $obj = null;
    /** @var \D3strukt0r\VotifierClient\ServerType\NuVotifier */
    private $obj2 = null;

    private $key = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAuyi7TXsufptucSYoVgZLonqFxtYvK0uJoxpExE+hcXRz3tR9jbXxtJv689/T+CHmvxJmli7g0CL0NucFDAdltat7bYu6AQMtWa7CYgvEtddwR5/ZMkZ1c3swK61fVeIsGE3oaA8Gdz1iBoG5njNmHtPzZm1CRWEYhUMMEPu9mBmqTRSYGrDr7NDJ5TL0frpLpPL/4rSTIOCJl0lBzzIT7supRmzppgeuWoh2M2lNUna329xtD5bhRPzmcIh4O2wC3jNQ+yh286mTcLG4AFBQgrSGfUHAZa6/l5rmF09Mg5CCvxqj05EBXafYGEH7bojtzDFC3J6NliAkMghk0jmrxQIDAQAB';

    public function setUp(): void
    {
        $this->obj = new NuVotifier('mock_host', 00000, $this->key);
        $this->obj2 = new NuVotifier('mock_host', 00000, null, true, 'mock_token');
    }

    public function tearDown(): void
    {
        $this->obj = null;
        $this->obj2 = null;
    }

    public function testInstanceOf(): void
    {
        $this->assertInstanceOf('D3strukt0r\VotifierClient\ServerType\NuVotifier', $this->obj);
    }

    public function testValues(): void
    {
        $this->assertSame('mock_host', $this->obj->getHost());
        $this->assertSame(00000, $this->obj->getPort());
        $key = wordwrap($this->key, 65, "\n", true);
        $key = <<<EOF
-----BEGIN PUBLIC KEY-----
$key
-----END PUBLIC KEY-----
EOF;
        $this->assertSame($key, $this->obj->getPublicKey());

        $this->assertTrue($this->obj2->isProtocolV2());
    }

    public function testHeaderVerification(): void
    {
        $this->assertFalse($this->obj->verifyConnection(false));
        $this->assertFalse($this->obj->verifyConnection('VOTIF'));
        $this->assertTrue($this->obj->verifyConnection('VOTIFIER 2.3.7 SOMETHING'));
    }

    public function testPackagePreparationV2(): void
    {
        $string = $this->obj->preparePackageV2(new ClassicVote('mock_user', 'mock_service', 'mock_address'), 'mock_challenge');
        $this->assertStringStartsWith('s:', $string);
        $this->assertStringEndsWith('{"signature":"LTsZweI\/1UwR+PHV9OKK0ULJRw2Ilavh17A8b6C0LBw=","payload":"{\"username\":\"mock_user\",\"serviceName\":\"mock_service\",\"timestamp\":null,\"address\":\"mock_address\",\"challenge\":\"mock_challenge\"}"}', $string);
    }
}
