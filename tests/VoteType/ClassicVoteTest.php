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

namespace D3strukt0r\VotifierClient\VoteType;

use PHPUnit\Framework\TestCase;

class ClassicVoteTest extends TestCase
{
    /** @var \D3strukt0r\VotifierClient\VoteType\ClassicVote */
    private $obj = null;

    public function setUp(): void
    {
        $this->obj = new ClassicVote('mock_user', 'mock_service', 'mock_address');
    }

    public function tearDown(): void
    {
        $this->obj = null;
    }

    public function testInstanceOf(): void
    {
        $this->assertInstanceOf('D3strukt0r\VotifierClient\VoteType\ClassicVote', $this->obj);
    }

    public function testValues(): void
    {
        $this->assertSame('mock_user', $this->obj->getUsername());
        $this->assertSame('mock_service', $this->obj->getServiceName());
        $this->assertSame('mock_address', $this->obj->getAddress());
        $this->assertNull($this->obj->getTimestamp());
    }

    public function testSetTimestamp(): void
    {
        $time = new \DateTime();
        $this->obj->setTimestamp($time);
        $this->assertSame($time->getTimestamp(), $this->obj->getTimestamp());
    }
}
