**\\D3strukt0r\\VotifierClient**

========
Messages
========

.. php:namespace:: D3strukt0r\VotifierClient
.. php:class:: Messages

    Internal use for translations.

    .. php:const:: NOT_VOTIFIER

    .. php:const:: NOT_SENT_PACKAGE

    .. php:const:: NOT_RECEIVED_PACKAGE

    .. php:const:: NUVOTIFIER_SERVER_ERROR

    .. php:staticmethod:: public get($messageCode[, $language = null]) -> string

        Translate and format a translation.

        :param int $messageCode: (Required) The message code to identify the required resource
        :param string $language: (Optional) The language code (e. g. en, de, es).

        :return: string — Returns the message in the specified language
