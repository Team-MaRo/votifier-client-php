**\\D3strukt0r\\VotifierClient**

====
Vote
====

.. php:namespace:: D3strukt0r\VotifierClient
.. php:class:: Vote

    This class is used for easy access to all classes and to send the votes.

    .. php:attr:: private $vote

        :php:class:`\\D3strukt0r\\VotifierClient\\VoteType\\VoteInterface` — The vote package

    .. php:attr:: private $server

        :php:class:`\\D3strukt0r\\VotifierClient\\ServerType\\ServerTypeInterface` — The server type information package

    .. php:method:: public __construct($vote, $serverType)

        Created a Vote object.

        :param \\D3strukt0r\\VotifierClient\\VoteType\\VoteInterface $vote: (Required) The vote package
        :param \\D3strukt0r\\VotifierClient\\ServerType\\ServerTypeInterface $serverType: (Required) The server type information package

    .. php:method:: public send()

        Sends the vote package to the server.

        :throws: :php:exc:`\\Exception`

