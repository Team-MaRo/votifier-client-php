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

namespace D3strukt0r\VotifierClient;

use D3strukt0r\VotifierClient\ServerType\ClassicVotifier;
use D3strukt0r\VotifierClient\VoteType\ClassicVote;
use PHPUnit\Framework\TestCase;

/**
 * Class VoteTest.
 *
 * @covers \D3strukt0r\VotifierClient\Vote
 *
 * @internal
 */
final class VoteTest extends TestCase
{
    /** @var Vote */
    private $obj;

    protected function setUp(): void
    {
        $this->obj = new Vote(
            new ClassicVote('mock_user', 'mock_service', 'mock_address'),
            new ClassicVotifier('mock_host', 00000, 'mock_key')
        );
    }

    protected function tearDown(): void
    {
        $this->obj = null;
    }

    public function testInstanceOf(): void
    {
        static::assertInstanceOf('D3strukt0r\VotifierClient\Vote', $this->obj);
    }

    /*public function testValidResult()
    {
        $this->assertTrue($this->obj->send());
    }*/
}
