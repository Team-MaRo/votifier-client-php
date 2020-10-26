**\\D3strukt0r\\Votifier\\Client**

======
Socket
======

.. php:namespace:: D3strukt0r\Votifier\Client
.. php:class:: Socket

    The class ServerConnection is used to create a connection to a server.

    .. php:attr:: private $socket

        resource — The connection to the server

    .. php:method:: public __destruct()

        Closes the connection when the object is destroyed.

    .. php:method:: public open($host, $port)

        Creates the ServerConnection object.

        :param string $host: The hostname or IP address
        :param int $port: The port of Votifier

        :throws: :php:exc:`D3strukt0r\\Votifier\\Client\\Exception\\Socket\\NoConnectionException` — If connection couldn't be established

    .. php:method:: public write($string)

        Sends a string to the server.

        :param string $string: The string which should be sent to the server

        :throws: :php:exc:`D3strukt0r\\Votifier\\Client\\Exception\\Socket\\NoConnectionException` — If connection has not been set up
        :throws: :php:exc:`D3strukt0r\\Votifier\\Client\\Exception\\Socket\\PackageNotSentException` — If there was an error sending the package

    .. php:method:: public read([$length = 64]) -> string

        Reads a string which is being received from the server.

        :param int $length: [optional] The length of the requested string

        :throws: :php:exc:`D3strukt0r\\Votifier\\Client\\Exception\\Socket\\NoConnectionException` — If connection has not been set up
        :throws: :php:exc:`D3strukt0r\\Votifier\\Client\\Exception\\Socket\\PackageNotReceivedException` — If there was an error receiving the package

        :returns: string — Returns the string received from the server
