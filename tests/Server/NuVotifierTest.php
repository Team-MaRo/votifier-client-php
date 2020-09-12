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

use D3strukt0r\VotifierClient\Exception\NotVotifierException;
use D3strukt0r\VotifierClient\Exception\NuVotifierChallengeInvalidException;
use D3strukt0r\VotifierClient\Exception\NuVotifierException;
use D3strukt0r\VotifierClient\Exception\NuVotifierSignatureInvalidException;
use D3strukt0r\VotifierClient\Exception\NuVotifierUnknownServiceException;
use D3strukt0r\VotifierClient\Exception\NuVotifierUsernameTooLongException;
use D3strukt0r\VotifierClient\Exception\Socket\PackageNotReceivedException;
use D3strukt0r\VotifierClient\Exception\Socket\PackageNotSentException;
use D3strukt0r\VotifierClient\Socket;
use D3strukt0r\VotifierClient\Vote\ClassicVote;
use PHPUnit\Framework\TestCase;

use function file_get_contents;

use const DIRECTORY_SEPARATOR;

/**
 * Class NuVotifierTest.
 *
 * @requires PHPUnit >= 8
 *
 * @covers \D3strukt0r\VotifierClient\Server\NuVotifier
 *
 * @internal
 */
final class NuVotifierTest extends TestCase
{
    /**
     * @var Socket The Socket tool class
     */
    private $socketStub;

    /**
     * @var NuVotifier The main class
     */
    private $nuvotifier;

    /**
     * @var NuVotifier The main class using V2
     */
    private $nuvotifierV2;

    protected function setUp(): void
    {
        $this->socketStub = $this->createStub(Socket::class);
        $key = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'votifier_public.key');
        $this->nuvotifier = (new NuVotifier())
            ->setSocket($this->socketStub)
            ->setHost('mock_host')
            ->setPort(0)
            ->setPublicKey($key)
        ;
        $this->nuvotifierV2 = (new NuVotifier())
            ->setSocket($this->socketStub)
            ->setHost('mock_host')
            ->setPort(0)
            ->setProtocolV2(true)
            ->setToken('mock_token')
        ;
    }

    protected function tearDown(): void
    {
        $this->socketStub = null;
        $this->nuvotifier = null;
        $this->nuvotifierV2 = null;
    }

    public function testInstanceOf(): void
    {
        $this->assertInstanceOf('D3strukt0r\VotifierClient\Server\NuVotifier', $this->nuvotifier);
    }

    public function testProtocolV2(): void
    {
        $this->nuvotifier->setProtocolV2(true);
        $this->assertTrue($this->nuvotifier->isProtocolV2());
    }

    public function testToken(): void
    {
        $this->nuvotifier->setToken('mock_token');
        $this->assertSame('mock_token', $this->nuvotifier->getToken());
    }

    public function testSendV1(): void
    {
        $this->socketStub
            ->method('read')
            ->willReturn('VOTIFIER 2 mock_challenge')
        ;

        $voteStub = $this->createStub(ClassicVote::class);

        $this->assertNull($this->nuvotifier->sendVote($voteStub));
    }

    public function testNotVotifierException(): void
    {
        $this->socketStub
            ->method('read')
            ->willReturn('SOMETHING_WEIRD')
        ;

        $voteStub = $this->createStub(ClassicVote::class);

        $this->expectException(NotVotifierException::class);
        $this->nuvotifierV2->sendVote($voteStub);
    }

    public function testNotVotifierException2(): void
    {
        $this->socketStub
            ->method('read')
            ->willReturn('VOTIFIER')
        ;

        $voteStub = $this->createStub(ClassicVote::class);

        $this->expectException(NotVotifierException::class);
        $this->nuvotifierV2->sendVote($voteStub);
    }

    public function testNotVotifierException3(): void
    {
        $this->socketStub
            ->method('read')
            ->willReturn('VOTIFIER 2')
        ;

        $voteStub = $this->createStub(ClassicVote::class);

        $this->expectException(NotVotifierException::class);
        $this->nuvotifierV2->sendVote($voteStub);
    }

    public function testPackageNotSentException(): void
    {
        $this->socketStub
            ->method('read')
            ->willReturn('VOTIFIER 2 mock_challenge')
        ;
        $this->socketStub
            ->method('write')
            ->willThrowException(new PackageNotSentException())
        ;

        $voteStub = $this->createStub(ClassicVote::class);

        $this->expectException(PackageNotSentException::class);
        $this->nuvotifierV2->sendVote($voteStub);
    }

    public function testPackageNotReceivedException(): void
    {
        $this->socketStub
            ->method('read')
            ->will($this->onConsecutiveCalls(
                'VOTIFIER 2 mock_challenge',
                $this->throwException(new PackageNotReceivedException())
            ))
        ;

        $voteStub = $this->createStub(ClassicVote::class);

        $this->expectException(PackageNotReceivedException::class);
        $this->nuvotifierV2->sendVote($voteStub);
    }

    public function testNuVotifierChallengeInvalidException(): void
    {
        $this->socketStub
            ->method('read')
            ->will($this->onConsecutiveCalls(
                'VOTIFIER 2 mock_challenge',
                '{"status":"error","cause":"CorruptedFrameException","error":"Challenge is not valid"}'
            ))
        ;

        $voteStub = $this->createStub(ClassicVote::class);

        $this->expectException(NuVotifierChallengeInvalidException::class);
        $this->nuvotifierV2->sendVote($voteStub);
    }

    public function testNuVotifierUnknownServiceException(): void
    {
        $this->socketStub
            ->method('read')
            ->will($this->onConsecutiveCalls(
                'VOTIFIER 2 mock_challenge',
                '{"status":"error","cause":"CorruptedFrameException","error":"Unknown service \'xxx\'"}'
            ))
        ;

        $voteStub = $this->createStub(ClassicVote::class);

        $this->expectException(NuVotifierUnknownServiceException::class);
        $this->nuvotifierV2->sendVote($voteStub);
    }

    public function testNuVotifierSignatureInvalidException(): void
    {
        $this->socketStub
            ->method('read')
            ->will($this->onConsecutiveCalls(
                'VOTIFIER 2 mock_challenge',
                '{"status":"error","cause":"CorruptedFrameException","error":"Signature is not valid (invalid token?)"}'
            ))
        ;

        $voteStub = $this->createStub(ClassicVote::class);

        $this->expectException(NuVotifierSignatureInvalidException::class);
        $this->nuvotifierV2->sendVote($voteStub);
    }

    public function testNuVotifierUsernameTooLongException(): void
    {
        $this->socketStub
            ->method('read')
            ->will($this->onConsecutiveCalls(
                'VOTIFIER 2 mock_challenge',
                '{"status":"error","cause":"CorruptedFrameException","error":"Username too long"}'
            ))
        ;

        $voteStub = $this->createStub(ClassicVote::class);

        $this->expectException(NuVotifierUsernameTooLongException::class);
        $this->nuvotifierV2->sendVote($voteStub);
    }

    public function testNuVotifierException(): void
    {
        $this->socketStub
            ->method('read')
            ->will($this->onConsecutiveCalls(
                'VOTIFIER 2 mock_challenge',
                '{"status":"error","cause":"CorruptedFrameException","error":"Some unknown error"}'
            ))
        ;

        $voteStub = $this->createStub(ClassicVote::class);

        $this->expectException(NuVotifierException::class);
        $this->nuvotifierV2->sendVote($voteStub);
    }

    public function testSend(): void
    {
        $this->socketStub
            ->method('read')
            ->will($this->onConsecutiveCalls('VOTIFIER 2 mock_challenge', '{"status":"ok"}'))
        ;

        $voteStub = $this->createStub(ClassicVote::class);

        $this->assertNull($this->nuvotifierV2->sendVote($voteStub));
    }
}
