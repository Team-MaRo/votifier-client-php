**\\D3strukt0r\\Votifier\\Client\\Server**

===============
ServerInterface
===============

.. php:namespace:: D3strukt0r\Votifier\Client\Server
.. php:interface:: ServerInterface

    The interface ServerInterface is used to define a PluginType on the server.

    .. php:method:: public getHost() -> string|null

        Gets the host.

        :returns: string|null — Returns the host

    .. php:method:: public setHost($host)

        Sets the host.

        :param string $host: The host

        :returns: $this — Returns the class itself, for doing multiple things at once

    .. php:method:: public getPort() -> int

        Gets the port.

        :returns: int — Returns the port

    .. php:method:: public setPort($port)

        Sets the port.

        :param int $port: The port

        :returns: $this — Returns the class itself, for doing multiple things at once

    .. php:method:: public getPublicKey() -> string|null

        Gets the public key.

        :returns: string|null — Returns the public key

    .. php:method:: public setPublicKey($publicKey)

        Sets the public key.

        :param string $publicKey: The public key

        :returns: $this — Returns the class itself, for doing multiple things at once

    .. php:method:: public sendVote(...$votes)

        Sends the vote packages to the server.

        :param D3strukt0r\\Votifier\\Client\\Vote\\VoteInterface $votes: The vote packages

        :throws: :php:exc:`Exception`
