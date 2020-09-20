**\\D3strukt0r\\Votifier\\Client\\Server**

========
Votifier
========

.. php:namespace:: D3strukt0r\Votifier\Client\Server
.. php:class:: Votifier

    The Class to access a server which uses the classic "Votifier" plugin.

    .. php:method:: public sendVote(...$votes)

        Sends the vote packages to the server.

        :param \\D3strukt0r\\Votifier\\Client\\Vote\\VoteInterface $votes: The vote packages

        :throws: :php:exc:`InvalidArgumentException` — If one required parameter wasn't set
        :throws: :php:exc:`D3strukt0r\\Votifier\\Client\\Exception\\Socket\\NoConnectionException` — If connection couldn't be established
        :throws: :php:exc:`D3strukt0r\\Votifier\\Client\\Exception\\Socket\\PackageNotSentException` — If there was an error receiving the package
        :throws: :php:exc:`D3strukt0r\\Votifier\\Client\\Exception\\Socket\\PackageNotReceivedException` — If there was an error sending the package
        :throws: :php:exc:`D3strukt0r\\Votifier\\Client\\Exception\\NotVotifierException` — If the server we are connected to is not a valid Votifier server

    .. php:method:: protected checkRequiredVariablesForSocket()

        Check that both host and port have been set.

        :throws: :php:exc:`InvalidArgumentException` — If one required parameter wasn't set

    .. php:method:: protected checkRequiredVariablesForPackage($vote)

        Check that service name, username, address, timestamp and public key have been set.

        :param D3strukt0r\\Votifier\\Client\\Vote\\VoteInterface $vote: The vote to check

        :throws: :php:exc:`InvalidArgumentException` — If one required parameter wasn't set

    .. php:method:: protected verifyConnection($header) -> bool

        Verifies that the connection is correct.

        :param string|null $header: The header that the plugin usually sends

        :returns: bool — Returns true if connections is available, otherwise false

    .. php:method:: protected preparePackage($vote) -> string

        Create encrypted package for default Votifier.

        :param \\D3strukt0r\\Votifier\\Client\\Vote\\VoteInterface $vote: The vote package with all the information

        :returns: string — Returns the string to be sent to the server
