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
        $this->vote = new Vote('play.orbitrondev.org',
                               '8192',
                               'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAg/6Tm/z9+iCWBj3fuIfJbUzDA1lcIgg4yYeNk72vaBGmjZTg6Vlc5UywyylDN+czWAacFGeIEoFxRKdfHy+I8Sl9sXvX68Xzo5FbS0pe8fa2CP6cgRU8bW4pgXQTEjjzBvis/UZqIO/MUQaBkbiyW7VQWYxD2aaaMA8V98/tH3NJsoeH9pfVLj8SE0TvZMolLRbKR1tYkeMN3vCuAYQn94yG4c1rRy7xJj5snpAatTrfRC3p2e3b5XBaq6x0aqli+QbovhbMHDl8FQAaj6zbpgTlDKqcyj2RWs4dNFfeEZGju/vXiOfkNZX1LIz9zlWSBzSSoi2IO/nAs3MRhXUvyQIDAQAB',
                               'D3strukt0r',
                               'Votifier-PHP-Client Test',
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
