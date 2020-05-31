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
    /** @var ClassicVote */
    private $obj;

    protected function setUp(): void
    {
        $this->obj = new ClassicVote('mock_user', 'mock_service', 'mock_address');
    }

    protected function tearDown(): void
    {
        $this->obj = null;
    }

    public function testInstanceOf(): void
    {
        static::assertInstanceOf('D3strukt0r\VotifierClient\VoteType\ClassicVote', $this->obj);
    }

    public function testValues(): void
    {
        static::assertSame('mock_user', $this->obj->getUsername());
        static::assertSame('mock_service', $this->obj->getServiceName());
        static::assertSame('mock_address', $this->obj->getAddress());
        static::assertNull($this->obj->getTimestamp());
    }

    public function testSetTimestamp(): void
    {
        $time = new \DateTime();
        $this->obj->setTimestamp($time);
        static::assertSame($time->getTimestamp(), $this->obj->getTimestamp());
    }
}
