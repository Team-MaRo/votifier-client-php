**\\D3strukt0r\\VotifierClient\\VoteType**

=============
VoteInterface
=============

.. php:namespace:: D3strukt0r\VotifierClient\VoteType
.. php:interface:: VoteInterface

    The interface VoteInterface will be used for different kinds of vote packages.

    .. php:method:: public getServiceName() -> string

        The name of the list/service.

        :returns: string — Returns the name of the list/service

    .. php:method:: public getUsername() -> string

        The username who wants to receive the rewards.

        :returns: string — Returns the username who wants to receive the rewards

    .. php:method:: public getAddress() -> string

        The IP Address of the user.

        :returns: string — Returns the IP Address of the user

    .. php:method:: public getTimestamp() -> int|null

        Get the time when the vote was sent.

        :returns: int|null — Returns the time when the vote was sent

    .. php:method:: public setTimestamp([$timestamp = null]) -> self

        Set the time when the vote will be sent.

        :param \\DateTime|null $timestamp: (Optional) Either give a wanted timestamp or it will use the current time

        :returns: self — Returns the class itself, for doing multiple things at once
