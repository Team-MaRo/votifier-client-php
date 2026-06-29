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

namespace D3strukt0r\Votifier\Client\Vote;

use PHPUnit\Framework\TestCase;

/**
 * Class ClassicVoteTest.
 *
 * @covers \D3strukt0r\Votifier\Client\Vote\ClassicVote
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
        self::assertInstanceOf('D3strukt0r\Votifier\Client\Vote\ClassicVote', $this->object);
    }

    public function testUsername(): void
    {
        $this->object->setUsername('mock_user');
        self::assertSame('mock_user', $this->object->getUsername());
    }

    public function testServiceName(): void
    {
        $this->object->setServiceName('mock_service');
        self::assertSame('mock_service', $this->object->getServiceName());
    }

    public function testAddress(): void
    {
        $this->object->setAddress('mock_address');
        self::assertSame('mock_address', $this->object->getAddress());
    }

    public function testTimestamp(): void
    {
        $time = new \DateTime();
        $this->object->setTimestamp($time);
        self::assertSame($time->getTimestamp(), $this->object->getTimestamp());
    }
}
