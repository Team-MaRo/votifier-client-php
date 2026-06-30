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
use D3strukt0r\Votifier\Client\Exception\Socket\NoConnectionException;
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
 * Class VotifierTest.
 *
 * @requires PHPUnit >= 8
 *
 * @covers   \D3strukt0r\Votifier\Client\Server\Votifier
 * @internal
 */
#[RequiresPhpunit('>= 8.0.0')]
#[CoversClass(Votifier::class)]
final class VotifierTest extends TestCase
{
    /**
     * @var Socket&Stub The Socket tool class
     */
    private $socketStub;

    /**
     * @var Votifier The main class
     */
    private $votifier;

    /**
     * @var VoteInterface A vote example
     */
    private $vote;

    protected function setUp(): void
    {
        $this->socketStub = self::createStub(Socket::class);
        $this->votifier = (new Votifier())
            ->setSocket($this->socketStub)
            ->setHost('mock_host')
            ->setPort(0)
            ->setPublicKey(file_get_contents(__DIR__.\DIRECTORY_SEPARATOR.'votifier_public.key'))
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
        $this->votifier = null;
    }

    public function testInstanceOf(): void
    {
        self::assertInstanceOf('D3strukt0r\Votifier\Client\Server\Votifier', $this->votifier);
    }

    public function testVerifyConnection(): void
    {
        $this->socketStub
            ->method('read')
            ->willReturn('SOMETHING_WEIRD')
        ;

        $this->expectException(NotVotifierException::class);
        $this->votifier->verifyConnection();
    }

    public function testVerifyConnectionSuccess(): void
    {
        $this->socketStub
            ->method('read')
            ->willReturn('VOTIFIER')
        ;

        self::assertNull($this->votifier->verifyConnection());
    }

    /**
     * @dataProvider checkRequiredVariablesForSocketProvider
     */
    #[DataProvider('checkRequiredVariablesForSocketProvider')]
    public function testCheckRequiredVariablesForSocket(?string $host, ?int $port): void
    {
        $votifier = new Votifier();
        if ($host !== null) {
            $votifier->setHost($host);
        }
        if ($port !== null) {
            $votifier->setPort($port);
        }

        $this->expectException(\InvalidArgumentException::class);
        $votifier->sendVote($this->vote);
    }

    public static function checkRequiredVariablesForSocketProvider(): iterable
    {
        return [
            'nothing set' => [null, null],
            // 'only host set' => ['mock_host', null], // Doesn't work, port is set by default
            'only port set' => [null, 0],
        ];
    }

    public function testNoConnectionException(): void
    {
        $this->socketStub
            ->method('open')
            ->willThrowException(new NoConnectionException())
        ;

        $this->expectException(NoConnectionException::class);
        $this->votifier->sendVote($this->vote);
    }

    public function testNotVotifierException(): void
    {
        $this->socketStub
            ->method('read')
            ->willReturn('SOMETHING_WEIRD')
        ;

        $this->expectException(NotVotifierException::class);
        $this->votifier->sendVote($this->vote);
    }

    /**
     * @dataProvider checkRequiredVariablesForPackageProvider
     */
    #[DataProvider('checkRequiredVariablesForPackageProvider')]
    public function testCheckRequiredVariablesForPackage(?string $serviceName, ?string $username, ?string $address, ?int $timestamp, ?string $key): void
    {
        $this->socketStub
            ->method('read')
            ->willReturn('VOTIFIER 1.9')
        ;

        $votifier = (new Votifier())
            ->setSocket($this->socketStub)
            ->setHost('mock_host')
            ->setPort(0)
        ;
        if ($key !== null) {
            $votifier->setPublicKey($key);
        }

        $voteStub = self::createStub(ClassicVote::class);
        $voteStub->method('getServiceName')->willReturn($serviceName);
        $voteStub->method('getUsername')->willReturn($username);
        $voteStub->method('getAddress')->willReturn($address);
        $voteStub->method('getTimestamp')->willReturn($timestamp);

        $this->expectException(\InvalidArgumentException::class);
        $votifier->sendVote($voteStub);
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
            'only timestamp set' => [
                null,
                null,
                null,
                (new \DateTime())->getTimestamp(),
                null,
            ],
            'only key' => [
                null,
                null,
                null,
                null,
                file_get_contents(__DIR__.\DIRECTORY_SEPARATOR.'votifier_public.key'),
            ],
        ];
    }

    public function testPackageNotSentException(): void
    {
        $this->socketStub
            ->method('read')
            ->willReturn('VOTIFIER 1.9')
        ;
        $this->socketStub
            ->method('write')
            ->willThrowException(new PackageNotSentException())
        ;

        $this->expectException(PackageNotSentException::class);
        $this->votifier->sendVote($this->vote);
    }

    public function testSend(): void
    {
        $this->socketStub
            ->method('read')
            ->willReturn('VOTIFIER 1.9')
        ;

        self::assertNull($this->votifier->sendVote($this->vote));
    }

    public function testInvalidPublicKey(): void
    {
        $this->socketStub
            ->method('read')
            ->willReturn('VOTIFIER 1.9')
        ;

        $votifier = (new Votifier())
            ->setSocket($this->socketStub)
            ->setHost('mock_host')
            ->setPort(0)
            ->setPublicKey('not a valid public key')
        ;

        $this->expectException(\InvalidArgumentException::class);
        $votifier->sendVote($this->vote);
    }
}
