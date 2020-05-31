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

use PHPUnit\Framework\TestCase;

/**
 * Class MessagesTest.
 *
 * @covers \D3strukt0r\VotifierClient\Messages
 *
 * @internal
 */
final class MessagesTest extends TestCase
{
    /** @var Vote */
    private $obj;

    public function testValidTranslation(): void
    {
        static::assertSame('The connection does not belong to Votifier', Messages::get(Messages::NOT_VOTIFIER));
        static::assertSame('Couldn\'t write to remote host', Messages::get(Messages::NOT_SENT_PACKAGE));
        static::assertSame('Unable to read server response', Messages::get(Messages::NOT_RECEIVED_PACKAGE));
        static::assertSame('Votifier server error: {0}: {1}', Messages::get(Messages::NUVOTIFIER_SERVER_ERROR));
    }

    public function testArgs(): void
    {
        static::assertSame(
            'Votifier server error: cause: error',
            Messages::get(Messages::NUVOTIFIER_SERVER_ERROR, 'en', [0 => 'cause', 1 => 'error'])
        );
        static::assertSame(
            'Votifier server error: cause: error',
            Messages::get(Messages::NUVOTIFIER_SERVER_ERROR, 'en', 'cause', 'error')
        );
    }
}
