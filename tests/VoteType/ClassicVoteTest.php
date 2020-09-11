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

namespace D3strukt0r\VotifierClient\VoteType;

use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Class ClassicVoteTest.
 *
 * @covers \D3strukt0r\VotifierClient\VoteType\ClassicVote
 *
 * @internal
 */
final class ClassicVoteTest extends TestCase
{
    /**
     * @var ClassicVote The main object
     */
    private $object;

    protected function setUp(): void
    {
        $this->object = new ClassicVote();
    }

    protected function tearDown(): void
    {
        $this->object = null;
    }

    public function testInstanceOf(): void
    {
        $this->assertInstanceOf('D3strukt0r\VotifierClient\VoteType\ClassicVote', $this->object);
    }

    public function testUsername(): void
    {
        $this->object->setUsername('mock_user');
        $this->assertSame('mock_user', $this->object->getUsername());
    }

    public function testServiceName(): void
    {
        $this->object->setServiceName('mock_service');
        $this->assertSame('mock_service', $this->object->getServiceName());
    }

    public function testAddress(): void
    {
        $this->object->setAddress('mock_address');
        $this->assertSame('mock_address', $this->object->getAddress());
    }

    public function testTimestamp(): void
    {
        $time = new DateTime();
        $this->object->setTimestamp($time);
        $this->assertSame($time->getTimestamp(), $this->object->getTimestamp());
    }
}
