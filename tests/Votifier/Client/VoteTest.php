<?php

namespace Votifier\Client;

use Votifier\Client\Vote;

class VoteTest extends \PHPUnit_Framework_TestCase
{
    private $vote = null;

    /**
     * Setup the test enviroment.
     */
    public function setUp()
    {
        $this->vote = new Vote('manuele.ddns.net',
                               '8192',
                               'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAiBUsS/82HP7cHpNT21ztgw8fonUACPiPTqVmw8M7o59H8vsTp0SHbOnmcOMeRmPRlzmBjjsV+WO7GNQeAFd8aW/nenRyuSojFZXeR9N6DFZyD7jz4lHZaJX0e4dkCe41kVt74Cki5rmDY4CtQhfkBeoMaf8eG/HjR/dEugDtizxL01vI4qVE+yriwYoUtw8tZkm1kbn0r4LmkeGVtZmPv1AY3FO8n9b3uxlViBmg5EI6NE89L/vldI9gda91hze4OkGn8UKMvSq5h5HuEMaX3428BMvpnGo6yRXmMjCmc0i0rc8Sr9UxbhMXSc55CX0pauF9e6aws8pUW6FmmlTHcQIDAQAB',
                               'D3strukt0r',
                               'VotifierPHP-Test',
                               '127.0.0.1');
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        $this->vote = null;
    }

    /**
     * Test instance of $this->vote.
     *
     * @test
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf('Votifier\Client\Vote', $this->vote);
    }

    /**
     * Test vote return the value true.
     *
     * @test
     */
    public function testValidResult()
    {
        $this->assertEquals(true, $this->vote->send());
    }
}
