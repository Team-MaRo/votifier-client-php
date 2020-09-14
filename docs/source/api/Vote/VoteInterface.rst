**\\D3strukt0r\\Votifier\\Client\\VoteType**

=============
VoteInterface
=============

.. php:namespace:: D3strukt0r\Votifier\Client\VoteType
.. php:interface:: VoteInterface

    The interface VoteInterface will be used for different kinds of vote packages.

    .. php:method:: public getServiceName() -> string|null

        The name of the list/service.

        :returns: string|null — Returns the name of the list/service

    .. php:method:: public setServiceName($serviceName)

        Sets the name of the list/service.

        :param string $serviceName: The name of the list/service

        :returns: $this — Returns the class itself, for doing multiple things at once

    .. php:method:: public getUsername() -> string|null

        Gets the username of the user who wants to receive the rewards.

        :returns: string|null — Returns the username who wants to receive the rewards

    .. php:method:: public setUsername($username)

        Sets the username of the user who wants to receive the rewards.

        :param string $username: The username of the user who wants to receive the rewards

        :returns: $this — Returns the class itself, for doing multiple things at once

    .. php:method:: public getAddress() -> string

        Gets the IP Address of the user.

        :returns: string — Returns the IP Address of the user

    .. php:method:: public setAddress($address)

        Sets the IP Address of the user.

        :param string $address: The IP address the user is sending a request from

        :returns: $this — Returns the class itself, for doing multiple things at once

    .. php:method:: public getTimestamp() -> int|null

        Gets the time when the vote was sent.

        :returns: int|null — Returns the time when the vote was sent, null otherwise

    .. php:method:: public setTimestamp([$timestamp = null])

        Sets the time when the vote will be sent.

        :param \\DateTime|null $timestamp: [optional] Either give a wanted timestamp or it will use the current time

        :returns: $this — Returns the class itself, for doing multiple things at once
