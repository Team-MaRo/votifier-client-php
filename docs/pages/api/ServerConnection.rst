**\\D3strukt0r\\VotifierClient**

================
ServerConnection
================

.. php:namespace:: D3strukt0r\VotifierClient
.. php:class:: ServerConnection

    The class ServerConnection is used to create a connection to a server.

    .. php:attr:: private $serverType

        :php:class:`\\D3strukt0r\\VotifierClient\\ServerType\\ServerTypeInterface` — The server type information package

    .. php:attr:: private $s

        resource — The username who wants to receive the rewards

    .. php:method:: public __construct($serverType)

        Creates the ServerConnection object.

        :param \\D3strukt0r\\VotifierClient\\ServerType\\ServerTypeInterface $serverType: (Required) The server type information package to connect to

        :throws: :php:exc:`\\Exception`

    .. php:method:: public __destruct()

        Closes the connection when the object is destroyed.

    .. php:method:: public send($string) -> bool

        Sends a string to the server.

        :param string $string: (Required) The string which should be sent to the server

        :returns: bool — Returns true if string was sent, or false if not

    .. php:method:: public receive([$length = 64]) -> string|null

        Reads a string which is being received from the server.

        :param int $length: (Optional) The length of the requested string

        :returns: string|null — Returns the string that was received from the server
