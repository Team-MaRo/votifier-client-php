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
use D3strukt0r\VotifierClient\Vote\VoteInterface;
use DateTime;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

use function file_get_contents;

use const DIRECTORY_SEPARATOR;

/**
 * Class NuVotifierTest.
 *
 * @requires PHPUnit >= 8
 *
 * @covers   \D3strukt0r\VotifierClient\Server\NuVotifier
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

    /**
     * @var VoteInterface A vote example
     */
    private $vote;

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
        $this->vote = (new ClassicVote())
            ->setServiceName('mock_service_name')
            ->setUsername('mock_username')
            ->setAddress('mock_0.0.0.0')
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

        $this->assertNull($this->nuvotifier->sendVote($this->vote));
    }

    public function notVotifierExceptionProvider(): array
    {
        return [
            'absolutely not votifier' => ['SOMETHING_WEIRD'],
            'only 1/3 of the part' => ['VOTIFIER'],
            'only 2/3 of the part' => ['VOTIFIER 2'],
        ];
    }

    /**
     * @param $readString
     *
     * @dataProvider notVotifierExceptionProvider
     */
    public function testNotVotifierException($readString): void
    {
        $this->socketStub
            ->method('read')
            ->willReturn($readString)
        ;

        $this->expectException(NotVotifierException::class);
        $this->nuvotifierV2->sendVote($this->vote);
    }

    public function checkRequiredVariablesForPackageProvider(): array
    {
        return [
            'nothing set' => [
                null,
                null,
                null,
                null,
                null,
            ],
            'only service name set' => [
                'mock_service_name',
                null,
                null,
                null,
                null,
            ],
            'only username set' => [
                null,
                'mock_username',
                null,
                null,
                null,
            ],
            'only service name & username set' => [
                'mock_service_name',
                'mock_username',
                null,
                null,
                null,
            ],
            'only address set' => [
                null,
                null,
                'mock_0.0.0.0',
                null,
                null,
            ],
            'only service name & username & address' => [
                'mock_service_name',
                'mock_username',
                'mock_0.0.0.0',
                null,
                null,
            ],
            'only timestamp set' => [
                null,
                null,
                null,
                (new DateTime())->getTimestamp(),
                null,
            ],
            'only token' => [
                null,
                null,
                null,
                null,
                'mock_token',
            ],
        ];
    }

    /**
     * @param $serviceName
     * @param $username
     * @param $address
     * @param $timestamp
     * @param $token
     *
     * @dataProvider checkRequiredVariablesForPackageProvider
     */
    public function testCheckRequiredVariablesForPackage($serviceName, $username, $address, $timestamp, $token): void
    {
        $this->socketStub
            ->method('read')
            ->willReturn('VOTIFIER 2 mock_challenge')
        ;

        $nuvotifierV2 = (new NuVotifier())
            ->setSocket($this->socketStub)
            ->setHost('mock_host')
            ->setPort(0)
            ->setProtocolV2(true)
        ;
        if (null !== $token) {
            $nuvotifierV2->setToken($token);
        }

        $voteStub = $this->createStub(ClassicVote::class);
        $voteStub->method('getServiceName')->willReturn($serviceName);
        $voteStub->method('getUsername')->willReturn($username);
        $voteStub->method('getAddress')->willReturn($address);
        $voteStub->method('getTimestamp')->willReturn($timestamp);

        $this->expectException(InvalidArgumentException::class);
        $nuvotifierV2->sendVote($voteStub);
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

        $this->expectException(PackageNotSentException::class);
        $this->nuvotifierV2->sendVote($this->vote);
    }

    public function testPackageNotReceivedException(): void
    {
        $this->socketStub
            ->method('read')
            ->will(
                $this->onConsecutiveCalls(
                    'VOTIFIER 2 mock_challenge',
                    $this->throwException(new PackageNotReceivedException())
                )
            )
        ;

        $this->expectException(PackageNotReceivedException::class);
        $this->nuvotifierV2->sendVote($this->vote);
    }

    public function nuVotifierResponseAfterSendVoteProvider(): array
    {
        return [
            'challenge invalid' => [
                '{"status":"error","cause":"CorruptedFrameException","error":"Challenge is not valid"}',
                NuVotifierChallengeInvalidException::class,
            ],
            'unknown service' => [
                '{"status":"error","cause":"CorruptedFrameException","error":"Unknown service \'xxx\'"}',
                NuVotifierUnknownServiceException::class,
            ],
            'signature invalid' => [
                '{"status":"error","cause":"CorruptedFrameException",' .
                '"error":"Signature is not valid (invalid token?)"}',
                NuVotifierSignatureInvalidException::class,
            ],
            'username too long' => [
                '{"status":"error","cause":"CorruptedFrameException","error":"Username too long"}',
                NuVotifierUsernameTooLongException::class,
            ],
            'unknown error' => [
                '{"status":"error","cause":"CorruptedFrameException","error":"Some unknown error"}',
                NuVotifierException::class,
            ],
        ];
    }

    /**
     * @param $errorMessage
     * @param $exceptionClass
     *
     * @dataProvider nuVotifierResponseAfterSendVoteProvider
     */
    public function testNuVotifierResponseAfterSendVote($errorMessage, $exceptionClass): void
    {
        $this->socketStub
            ->method('read')
            ->will(
                $this->onConsecutiveCalls(
                    'VOTIFIER 2 mock_challenge',
                    $errorMessage
                )
            )
        ;

        $this->expectException($exceptionClass);
        $this->nuvotifierV2->sendVote($this->vote);
    }

    public function testSend(): void
    {
        $this->socketStub
            ->method('read')
            ->will($this->onConsecutiveCalls('VOTIFIER 2 mock_challenge', '{"status":"ok"}'))
        ;

        $this->assertNull($this->nuvotifierV2->sendVote($this->vote));
    }
}
