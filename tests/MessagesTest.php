<?php

/**
 * Votifier PHP Client
 *
 * @package   VotifierClient
 *
 * @author    Manuele Vaccari <manuele.vaccari@gmail.com>
 * @copyright Copyright (c) 2017-2018 Manuele Vaccari <manuele.vaccari@gmail.com>
 * @license   https://github.com/D3strukt0r/Votifier-PHP-Client/blob/master/LICENSE.md MIT License
 *
 * @link      https://github.com/D3strukt0r/Votifier-PHP-Client
 */

namespace D3strukt0r\VotifierClient;

use PHPUnit\Framework\TestCase;

class MessagesTest extends TestCase
{
    /** @var \D3strukt0r\VotifierClient\Vote */
    private $obj = null;

    public function testValidTranslation()
    {
        $this->assertSame('The connection does not belong to Votifier', Messages::get(Messages::NOT_VOTIFIER));
        $this->assertSame('Couldn\'t write to remote host', Messages::get(Messages::NOT_SENT_PACKAGE));
        $this->assertSame('Unable to read server response', Messages::get(Messages::NOT_RECEIVED_PACKAGE));
        $this->assertSame('Votifier server error: {0}: {1}', Messages::get(Messages::NUVOTIFIER_SERVER_ERROR));
    }

    public function testArgs()
    {
        $this->assertSame(
            'Votifier server error: cause: error',
            Messages::get(Messages::NUVOTIFIER_SERVER_ERROR, null, array(0 => 'cause', 1 => 'error'))
        );
        $this->assertSame(
            'Votifier server error: cause: error',
            Messages::get(Messages::NUVOTIFIER_SERVER_ERROR, null, 'cause', 'error')
        );
    }
}
