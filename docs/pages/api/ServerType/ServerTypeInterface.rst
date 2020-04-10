**\\D3strukt0r\\VotifierClient\\ServerType**

===================
ServerTypeInterface
===================

.. php:namespace:: D3strukt0r\VotifierClient\ServerType
.. php:interface:: ServerTypeInterface

    The interface ServerTypeInterface is used to define a PluginType on the server.

    .. php:method:: public getHost() -> string

        Returns the host.

        :returns: string — Returns the host

    .. php:method:: public getPort() -> int

        Returns the port.

        :returns: int — Returns the port

    .. php:method:: public getPublicKey() -> string

        Returns the public key.

        :returns: string — Returns the public key

    .. php:method:: public verifyConnection($header) -> bool

        Verifies that the connection is correct.

        :param bool|string $header: (Required) The header that the plugin usually sends

        :returns: bool — Returns true if connections is available, otherwise false

    .. php:method:: public send($connection, $vote)

        Sends the vote package to the server.

        :param \\D3strukt0r\\VotifierClient\\ServerConnection $connection: (Required) The connection type to the plugin
        :param \\D3strukt0r\\VotifierClient\\VoteType\\VoteInterface $vote: (Required) The vote type package

        :throws: :php:exc:`\\Exception`
