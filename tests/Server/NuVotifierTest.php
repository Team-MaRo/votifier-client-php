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

use D3strukt0r\Votifier\Client\Exception\NotVotifierException;
use D3strukt0r\Votifier\Client\Exception\NuVotifierChallengeInvalidException;
use D3strukt0r\Votifier\Client\Exception\NuVotifierException;
use D3strukt0r\Votifier\Client\Exception\NuVotifierSignatureInvalidException;
use D3strukt0r\Votifier\Client\Exception\NuVotifierUnknownServiceException;
use D3strukt0r\Votifier\Client\Exception\NuVotifierUsernameTooLongException;
use D3strukt0r\Votifier\Client\Exception\Socket\PackageNotReceivedException;
use D3strukt0r\Votifier\Client\Exception\Socket\PackageNotSentException;
use D3strukt0r\Votifier\Client\Socket;
use D3strukt0r\Votifier\Client\Vote\ClassicVote;
use D3strukt0r\Votifier\Client\Vote\VoteInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RequiresPhpunit;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;

/**
 * Class NuVotifierTest.
 *
 * @requires PHPUnit >= 8
 *
 * @covers   \D3strukt0r\Votifier\Client\Server\NuVotifier
 * @internal
 */
#[RequiresPhpunit('>= 8.0.0')]
#[CoversClass(NuVotifier::class)]
final class NuVotifierTest extends TestCase
{
    /**
     * @var Socket&Stub The Socket tool class
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
        $this->socketStub = self::createStub(Socket::class);
        $key = file_get_contents(__DIR__.\DIRECTORY_SEPARATOR.'votifier_public.key');
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
        self::assertInstanceOf('D3strukt0r\Votifier\Client\Server\NuVotifier', $this->nuvotifier);
    }

    public function testProtocolV2(): void
    {
        $this->nuvotifier->setProtocolV2(true);
        self::assertTrue($this->nuvotifier->isProtocolV2());
    }

    public function testToken(): void
    {
        $this->nuvotifier->setToken('mock_token');
        self::assertSame('mock_token', $this->nuvotifier->getToken());
    }

    /**
     * @dataProvider notVotifierExceptionProvider
     */
    #[DataProvider('notVotifierExceptionProvider')]
    public function testVerifyConnection(string $readString): void
    {
        $this->socketStub
            ->method('read')
            ->willReturn($readString)
        ;

        $this->expectException(NotVotifierException::class);
        $this->nuvotifier->verifyConnection();
    }

    public function testVerifyConnectionSuccess(): void
    {
        $this->socketStub
            ->method('read')
            ->willReturn('VOTIFIER 2 mock_challenge')
        ;

        self::assertNull($this->nuvotifier->verifyConnection());
    }

    public function testSendV1(): void
    {
        $this->socketStub
            ->method('read')
            ->willReturn('VOTIFIER 2 mock_challenge')
        ;

        self::assertNull($this->nuvotifier->sendVote($this->vote));
    }

    /**
     * @dataProvider notVotifierExceptionProvider
     */
    #[DataProvider('notVotifierExceptionProvider')]
    public function testNotVotifierException(string $readString): void
    {
        $this->socketStub
            ->method('read')
            ->willReturn($readString)
        ;

        $this->expectException(NotVotifierException::class);
        $this->nuvotifierV2->sendVote($this->vote);
    }

    public static function notVotifierExceptionProvider(): iterable
    {
        return [
            'absolutely not votifier' => ['SOMETHING_WEIRD'],
            'only 1/3 of the part' => ['VOTIFIER'],
            'only 2/3 of the part' => ['VOTIFIER 2'],
        ];
    }

    /**
     * @dataProvider checkRequiredVariablesForPackageProvider
     */
    #[DataProvider('checkRequiredVariablesForPackageProvider')]
    public function testCheckRequiredVariablesForPackage(?string $serviceName, ?string $username, ?string $address, ?int $timestamp, ?string $token): void
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
        if ($token !== null) {
            $nuvotifierV2->setToken($token);
        }

        $voteStub = self::createStub(ClassicVote::class);
        $voteStub->method('getServiceName')->willReturn($serviceName);
        $voteStub->method('getUsername')->willReturn($username);
        $voteStub->method('getAddress')->willReturn($address);
        $voteStub->method('getTimestamp')->willReturn($timestamp);

        $this->expectException(\InvalidArgumentException::class);
        $nuvotifierV2->sendVote($voteStub);
    }

    public static function checkRequiredVariablesForPackageProvider(): iterable
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
                (new \DateTime())->getTimestamp(),
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
        $read = 0;
        $this->socketStub
            ->method('read')
            ->willReturnCallback(static function () use (&$read) {
                if ($read++ === 0) {
                    return 'VOTIFIER 2 mock_challenge';
                }

                throw new PackageNotReceivedException();
            })
        ;

        $this->expectException(PackageNotReceivedException::class);
        $this->nuvotifierV2->sendVote($this->vote);
    }

    /**
     * @dataProvider nuVotifierResponseAfterSendVoteProvider
     */
    #[DataProvider('nuVotifierResponseAfterSendVoteProvider')]
    public function testNuVotifierResponseAfterSendVote(string $errorMessage, string $exceptionClass): void
    {
        $reads = ['VOTIFIER 2 mock_challenge', $errorMessage];
        $read = 0;
        $this->socketStub
            ->method('read')
            ->willReturnCallback(static function () use (&$read, $reads) {
                return $reads[$read++];
            })
        ;

        $this->expectException($exceptionClass);
        $this->nuvotifierV2->sendVote($this->vote);
    }

    public static function nuVotifierResponseAfterSendVoteProvider(): iterable
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
                '{"status":"error","cause":"CorruptedFrameException",'
                .'"error":"Signature is not valid (invalid token?)"}',
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

    public function testSend(): void
    {
        $reads = ['VOTIFIER 2 mock_challenge', '{"status":"ok"}'];
        $read = 0;
        $this->socketStub
            ->method('read')
            ->willReturnCallback(static function () use (&$read, $reads) {
                return $reads[$read++];
            })
        ;

        self::assertNull($this->nuvotifierV2->sendVote($this->vote));
    }
}
