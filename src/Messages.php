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

/**
 * Internal use for translations.
 */
class Messages
{
    const NOT_VOTIFIER = 1;
    const NOT_SENT_PACKAGE = 2;
    const NOT_RECEIVED_PACKAGE = 3;

    const NUVOTIFIER_SERVER_ERROR = 100;

    /**
     * Translate and format a translation.
     *
     * @param int         $messageCode (Required) The message code to identify the required resource
     * @param string|null $language    (Optional) The language code (e. g. en, de, es).
     *
     * @return string returns the message in the specified language
     */
    public static function get(int $messageCode, string $language = null): string
    {
        $messages = [
            'en' => [
                self::NOT_VOTIFIER => 'The connection does not belong to Votifier',
                self::NOT_SENT_PACKAGE => 'Couldn\'t write to remote host',
                self::NOT_RECEIVED_PACKAGE => 'Unable to read server response',
                self::NUVOTIFIER_SERVER_ERROR => 'Votifier server error: {0}: {1}',
            ],
        ];

        $requestedMessage = $messages[$language ?: 'en'][$messageCode];

        $argsCount = \func_num_args();

        if ($argsCount > 2) {
            $firstArg = func_get_arg(2);
            if (\is_array($firstArg)) {
                foreach ($firstArg as $key => $value) {
                    $requestedMessage = str_replace('{'.$key.'}', $value, $requestedMessage);
                }
            } else {
                for ($i = 2; $i < $argsCount; ++$i) {
                    $arg = func_get_arg($i);
                    $requestedMessage = str_replace('{'.($i - 2).'}', $arg, $requestedMessage);
                }
            }
        }

        return $requestedMessage;
    }
}
