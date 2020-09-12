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
use D3strukt0r\VotifierClient\VoteType\ClassicVote;
use PHPUnit\Framework\TestCase;

use function file_get_contents;

use const DIRECTORY_SEPARATOR;

/**
 * Class NuVotifierTest.
 *
 * @covers \D3strukt0r\VotifierClient\ServerType\NuVotifier
 *
 * @internal
 */
final class NuVotifierTest extends TestCase
{
    /**
     * @var NuVotifier The main class
     */
    private $object;

    /**
     * @var NuVotifier The main class using V2
     */
    private $objectV2;

    protected function setUp(): void
    {
        $key = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'votifier_public.key');
        $this->object = (new NuVotifier())
            ->setHost('mock_host')
            ->setPort(0)
            ->setPublicKey($key)
        ;

        $this->objectV2 = (new NuVotifier())
            ->setHost('mock_host')
            ->setPort(0)
            ->setProtocolV2(true)
            ->setToken('mock_token')
        ;
    }

    protected function tearDown(): void
    {
        $this->object = null;
        $this->objectV2 = null;
    }

    public function testInstanceOf(): void
    {
        $this->assertInstanceOf('D3strukt0r\VotifierClient\ServerType\NuVotifier', $this->object);
        $this->assertInstanceOf('D3strukt0r\VotifierClient\ServerType\NuVotifier', $this->objectV2);
    }

    public function testProtocolV2(): void
    {
        $this->object->setProtocolV2(true);
        $this->assertTrue($this->object->isProtocolV2());
    }

    public function testToken(): void
    {
        $this->object->setToken('mock_token');
        $this->assertSame('mock_token', $this->object->getToken());
    }

    /**
     * @requires PHPUnit >= 8
     */
    public function testSendV1(): void
    {
        $stub = $this->createStub(NuVotifier::class);
        $stub->method('read')->willReturn('VOTIFIER');
        $stub->method('write')->willReturn(null);


        $stubServerConnection = $this->createStub(ServerConnection::class);
        $stubServerConnection
            ->method('receive')
            ->willReturn('VOTIFIER')
        ;
        $stubServerConnection
            ->method('send')
            ->willReturn(true)
        ;

        $stubVote = $this->createStub(ClassicVote::class);

        $this->assertNull($this->object->sendVote($stubServerConnection, $stubVote));
    }

    /**
     * @requires PHPUnit >= 8
     */
    public function testNotVotifierException(): void
    {
        $stubServerConnection = $this->createStub(ServerConnection::class);
        $stubServerConnection
            ->method('receive')
            ->willReturn('SOMETHING_WEIRD')
        ;

        $stubVote = $this->createStub(ClassicVote::class);

        $this->expectException(NotVotifierException::class);
        $this->objectV2->sendVote($stubServerConnection, $stubVote);
    }

    /**
     * @requires PHPUnit >= 8
     */
    public function testNotVotifierException2(): void
    {
        $stubServerConnection = $this->createStub(ServerConnection::class);
        $stubServerConnection
            ->method('receive')
            ->willReturn('VOTIFIER')
        ;

        $stubVote = $this->createStub(ClassicVote::class);

        $this->expectException(NotVotifierException::class);
        $this->objectV2->sendVote($stubServerConnection, $stubVote);
    }

    /**
     * @requires PHPUnit >= 8
     */
    public function testNotVotifierException3(): void
    {
        $stubServerConnection = $this->createStub(ServerConnection::class);
        $stubServerConnection
            ->method('receive')
            ->willReturn('VOTIFIER 2')
        ;

        $stubVote = $this->createStub(ClassicVote::class);

        $this->expectException(NotVotifierException::class);
        $this->objectV2->sendVote($stubServerConnection, $stubVote);
    }

    /**
     * @requires PHPUnit >= 8
     */
    public function testPackageNotSentException(): void
    {
        $stubServerConnection = $this->createStub(ServerConnection::class);
        $stubServerConnection
            ->method('receive')
            ->willReturn('VOTIFIER 2 mock_challenge')
        ;
        $stubServerConnection
            ->method('send')
            ->willReturn(false)
        ;

        $stubVote = $this->createStub(ClassicVote::class);

        $this->expectException(PackageNotSentException::class);
        $this->objectV2->sendVote($stubServerConnection, $stubVote);
    }

    /**
     * @requires PHPUnit >= 8
     */
    public function testPackageNotReceivedException(): void
    {
        $stubServerConnection = $this->createStub(ServerConnection::class);
        $stubServerConnection
            ->method('receive')
            ->will($this->onConsecutiveCalls('VOTIFIER 2 mock_challenge', null))
        ;
        $stubServerConnection->method('send')
            ->willReturn(true)
        ;

        $stubVote = $this->createStub(ClassicVote::class);

        $this->expectException(PackageNotReceivedException::class);
        $this->objectV2->sendVote($stubServerConnection, $stubVote);
    }

    /**
     * @requires PHPUnit >= 8
     */
    public function testNuVotifierChallengeInvalidException(): void
    {
        $stubServerConnection = $this->createStub(ServerConnection::class);
        $stubServerConnection
            ->method('receive')
            ->will($this->onConsecutiveCalls(
                'VOTIFIER 2 mock_challenge',
                '{"status":"error","cause":"CorruptedFrameException","error":"Challenge is not valid"}'
            ))
        ;
        $stubServerConnection->method('send')
            ->willReturn(true)
        ;

        $stubVote = $this->createStub(ClassicVote::class);

        $this->expectException(NuVotifierChallengeInvalidException::class);
        $this->objectV2->sendVote($stubServerConnection, $stubVote);
    }

    /**
     * @requires PHPUnit >= 8
     */
    public function testNuVotifierUnknownServiceException(): void
    {
        $stubServerConnection = $this->createStub(ServerConnection::class);
        $stubServerConnection
            ->method('receive')
            ->will($this->onConsecutiveCalls(
                'VOTIFIER 2 mock_challenge',
                '{"status":"error","cause":"CorruptedFrameException","error":"Unknown service \'xxx\'"}'
            ))
        ;
        $stubServerConnection->method('send')
            ->willReturn(true)
        ;

        $stubVote = $this->createStub(ClassicVote::class);

        $this->expectException(NuVotifierUnknownServiceException::class);
        $this->objectV2->sendVote($stubServerConnection, $stubVote);
    }

    /**
     * @requires PHPUnit >= 8
     */
    public function testNuVotifierSignatureInvalidException(): void
    {
        $stubServerConnection = $this->createStub(ServerConnection::class);
        $stubServerConnection
            ->method('receive')
            ->will($this->onConsecutiveCalls(
                'VOTIFIER 2 mock_challenge',
                '{"status":"error","cause":"CorruptedFrameException","error":"Signature is not valid (invalid token?)"}'
            ))
        ;
        $stubServerConnection->method('send')
            ->willReturn(true)
        ;

        $stubVote = $this->createStub(ClassicVote::class);

        $this->expectException(NuVotifierSignatureInvalidException::class);
        $this->objectV2->sendVote($stubServerConnection, $stubVote);
    }

    /**
     * @requires PHPUnit >= 8
     */
    public function testNuVotifierUsernameTooLongException(): void
    {
        $stubServerConnection = $this->createStub(ServerConnection::class);
        $stubServerConnection
            ->method('receive')
            ->will($this->onConsecutiveCalls(
                'VOTIFIER 2 mock_challenge',
                '{"status":"error","cause":"CorruptedFrameException","error":"Username too long"}'
            ))
        ;
        $stubServerConnection->method('send')
            ->willReturn(true)
        ;

        $stubVote = $this->createStub(ClassicVote::class);

        $this->expectException(NuVotifierUsernameTooLongException::class);
        $this->objectV2->sendVote($stubServerConnection, $stubVote);
    }

    /**
     * @requires PHPUnit >= 8
     */
    public function testNuVotifierException(): void
    {
        $stubServerConnection = $this->createStub(ServerConnection::class);
        $stubServerConnection
            ->method('receive')
            ->will($this->onConsecutiveCalls(
                'VOTIFIER 2 mock_challenge',
                '{"status":"error","cause":"CorruptedFrameException","error":"Some unknown error"}'
            ))
        ;
        $stubServerConnection->method('send')
            ->willReturn(true)
        ;

        $stubVote = $this->createStub(ClassicVote::class);

        $this->expectException(NuVotifierException::class);
        $this->objectV2->sendVote($stubServerConnection, $stubVote);
    }

    /**
     * @requires PHPUnit >= 8
     */
    public function testSend(): void
    {
        $stubServerConnection = $this->createStub(ServerConnection::class);
        $stubServerConnection
            ->method('receive')
            ->will($this->onConsecutiveCalls('VOTIFIER 2 mock_challenge', '{"status":"ok"}'))
        ;
        $stubServerConnection->method('send')
            ->willReturn(true)
        ;

        $stubVote = $this->createStub(ClassicVote::class);

        $this->assertNull($this->objectV2->sendVote($stubServerConnection, $stubVote));
    }
}
