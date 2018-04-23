<?php

namespace D3strukt0r\VotifierClient\VoteType;

use PHPUnit\Framework\TestCase;

class ClassicVoteTest extends TestCase
{
    /** @var \D3strukt0r\VotifierClient\VoteType\ClassicVote */
    private $obj = null;

    public function setUp()
    {
        $this->obj = new ClassicVote('mock_user', 'mock_service', 'mock_address');
    }

    public function tearDown()
    {
        $this->obj = null;
    }

    public function testInstanceOf()
    {
        $this->assertInstanceOf('D3strukt0r\VotifierClient\VoteType\ClassicVote', $this->obj);
    }

    public function testValues()
    {
        $this->assertSame('mock_user', $this->obj->getUsername());
        $this->assertSame('mock_service', $this->obj->getServiceName());
        $this->assertSame('mock_address', $this->obj->getAddress());
        $this->assertNull($this->obj->getTimestamp());
    }

    public function testSetTimestamp()
    {
        $time = new \DateTime();
        $this->obj->setTimestamp($time);
        $this->assertSame($time->getTimestamp(), $this->obj->getTimestamp());
    }
}
