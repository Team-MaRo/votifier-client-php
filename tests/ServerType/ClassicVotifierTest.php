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
use D3strukt0r\VotifierClient\Exception\PackageNotSentException;
use D3strukt0r\VotifierClient\ServerConnection;
use D3strukt0r\VotifierClient\VoteType\ClassicVote;
use PHPUnit\Framework\TestCase;

use function file_get_contents;

use const DIRECTORY_SEPARATOR;

/**
 * Class ClassicVotifierTest.
 *
 * @covers \D3strukt0r\VotifierClient\ServerType\ClassicVotifier
 *
 * @internal
 */
final class ClassicVotifierTest extends TestCase
{
    public function testInstanceOf(): void
    {
        $key = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'votifier_public.key');
        $object = (new ClassicVotifier())
            ->setHost('mock_host')
            ->setPort(0)
            ->setPublicKey($key)
        ;
        $this->assertInstanceOf('D3strukt0r\VotifierClient\ServerType\ClassicVotifier', $object);
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
        $this->object->send($stubServerConnection, $stubVote);
    }

    /**
     * @requires PHPUnit >= 8
     */
    public function testPackageNotSentException(): void
    {
        $stubServerConnection = $this->createStub(ServerConnection::class);
        $stubServerConnection
            ->method('receive')
            ->willReturn('VOTIFIER')
        ;
        $stubServerConnection
            ->method('send')
            ->willReturn(false)
        ;

        $stubVote = $this->createStub(ClassicVote::class);

        $this->expectException(PackageNotSentException::class);
        $this->object->send($stubServerConnection, $stubVote);
    }

    /**
     * @requires PHPUnit >= 8
     */
    public function testSend(): void
    {
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

        $this->assertNull($this->object->send($stubServerConnection, $stubVote));
    }
}
