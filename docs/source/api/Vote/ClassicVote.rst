**\\D3strukt0r\\Votifier\\Client\\Vote**

===========
ClassicVote
===========

.. php:namespace:: D3strukt0r\Votifier\Client\Vote
.. php:class:: ClassicVote

    The classic vote package can be used by most plugins.

    .. php:attr:: protected $serviceName

        string — The name of the list/service

    .. php:attr:: protected $username

        string — The username who wants to receive the rewards

    .. php:attr:: protected $address

        string — The IP Address of the user

    .. php:attr:: protected $timestamp

        :php:class:`DateTime` — The time when the vote will be sent

    .. php:method:: public getServiceName() -> string|null

        The name of the list/service.

        :returns: string|null — Returns the name of the list/service

    .. php:method:: public setServiceName($serviceName) -> $this

        Sets the name of the list/service.

        :param string $serviceName: The name of the list/service

        :returns: $this — Returns the class itself, for doing multiple things at once

    .. php:method:: public getUsername() -> string|null

        Gets the username of the user who wants to receive the rewards.

        :returns: string|null — Returns the username who wants to receive the rewards

    .. php:method:: public setUsername($username) -> $this

        Sets the username of the user who wants to receive the rewards.

        :param string $username: The username of the user who wants to receive the rewards

        :returns: $this — Returns the class itself, for doing multiple things at once

    .. php:method:: public getAddress() -> string

        Gets the IP Address of the user.

        :returns: string — Returns the IP Address of the user

    .. php:method:: public setAddress($address) -> $this

        Sets the IP Address of the user.

        :param string $address: The IP address the user is sending a request from

        :returns: $this — Returns the class itself, for doing multiple things at once

    .. php:method:: public getTimestamp() -> int|null

        Gets the time when the vote was sent.

        :returns: int|null — Returns the time when the vote was sent, null otherwise

    .. php:method:: public setTimestamp([$timestamp = null]) -> $this

        Sets the time when the vote will be sent.

        :param DateTime|null $timestamp: [optional] Either give a wanted timestamp or it will use the current time

        :returns: $this — Returns the class itself, for doing multiple things at once
