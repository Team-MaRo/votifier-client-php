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

namespace D3strukt0r\Votifier\Client\Server;

use D3strukt0r\Votifier\Client\Exception\NotVotifierException;
use D3strukt0r\Votifier\Client\Exception\Socket\NoConnectionException;
use D3strukt0r\Votifier\Client\Exception\Socket\PackageNotSentException;
use D3strukt0r\Votifier\Client\Socket;
use D3strukt0r\Votifier\Client\Vote\ClassicVote;
use D3strukt0r\Votifier\Client\Vote\VoteInterface;
use DateTime;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

use function file_get_contents;

use const DIRECTORY_SEPARATOR;

/**
 * Class VotifierTest.
 *
 * @requires PHPUnit >= 8
 *
 * @covers   \D3strukt0r\Votifier\Client\Server\Votifier
 *
 * @internal
 */
final class VotifierTest extends TestCase
{
    /**
     * @var Socket The Socket tool class
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
        $this->socketStub = $this->createStub(Socket::class);
        $this->votifier = (new Votifier())
            ->setSocket($this->socketStub)
            ->setHost('mock_host')
            ->setPort(0)
            ->setPublicKey(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'votifier_public.key'))
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
        $this->assertInstanceOf('D3strukt0r\Votifier\Client\Server\Votifier', $this->votifier);
    }

    public function checkRequiredVariablesForSocketProvider(): array
    {
        return [
            'nothing set' => [null, null],
            // 'only host set' => ['mock_host', null], // Doesn't work, port is set by default
            'only port set' => [null, 0],
        ];
    }

    /**
     * @param $host
     * @param $port
     *
     * @dataProvider checkRequiredVariablesForSocketProvider
     */
    public function testCheckRequiredVariablesForSocket($host, $port): void
    {
        $votifier = new Votifier();
        if (null !== $host) {
            $votifier->setHost($host);
        }
        if (null !== $port) {
            $votifier->setPort($port);
        }

        $this->expectException(InvalidArgumentException::class);
        $votifier->sendVote($this->vote);
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
            'only timestamp set' => [
                null,
                null,
                null,
                (new DateTime())->getTimestamp(),
                null,
            ],
            'only key' => [
                null,
                null,
                null,
                null,
                file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'votifier_public.key'),
            ],
        ];
    }

    /**
     * @param $serviceName
     * @param $username
     * @param $address
     * @param $timestamp
     * @param $key
     *
     * @dataProvider checkRequiredVariablesForPackageProvider
     */
    public function testCheckRequiredVariablesForPackage($serviceName, $username, $address, $timestamp, $key): void
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
        if (null !== $key) {
            $votifier->setPublicKey($key);
        }

        $voteStub = $this->createStub(ClassicVote::class);
        $voteStub->method('getServiceName')->willReturn($serviceName);
        $voteStub->method('getUsername')->willReturn($username);
        $voteStub->method('getAddress')->willReturn($address);
        $voteStub->method('getTimestamp')->willReturn($timestamp);

        $this->expectException(InvalidArgumentException::class);
        $votifier->sendVote($voteStub);
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

        $this->assertNull($this->votifier->sendVote($this->vote));
    }
}
