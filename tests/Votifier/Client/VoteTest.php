<?php

namespace Votifier\Client;

use PHPUnit\Framework\TestCase;

class VoteTest extends TestCase
{
    /** @var \Votifier\Client\Vote */
    private $vote = null;

    /**
     * Setup the test enviroment.
     */
    public function setUp()
    {
        $this->vote = new Vote(
            getenv('VOTIFIER_HOST') ?: '89.163.242.97',
            getenv('VOTIFIER_PORT') ?: 35670,
            getenv('VOTIFIER_KEY') ?: 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAhQGwChJARqa2/e3v6MLi6QXj+Z0vf8aRuruczNeCF5vXqmZWl2m31fH2bh5yOXodseEonRoV7xIbQEMUqh2fWA1PkglAqOIKawOHxjoDtGWpdi1gaAOrCGDJJxcNH/y5E3oQboQfhbR8FB2w1E0JGlwRho9KWpLSifKXhZwivuymjGaOi65hjifbC+Uw4ESzcUdL9e6xR+pn49/99vX5+duDRhTSAwei6fM/uTW4342iL2S7oJh+Jo1ceLgmmXTBvosQ3J93u1SqFCPYQS9Io8fq7MdyIx27HIqidsEk1N3v0Okn3YLkNp8d+YHJ6tCAcQvtfS7B2TWXCNV9fGwVNwIDAQAB',
            'D3strukt0r',
            'Votifier-PHP-Client Test',
            '127.0.0.1'
        );
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
        $this->assertTrue($this->vote->send());
    }
}
