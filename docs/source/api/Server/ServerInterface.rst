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

    .. php:method:: public verifyConnection()

        Checks if the server actually belongs to Votifier.

        :throws: :php:exc:`InvalidArgumentException`
            
            If one required parameter wasn't set
        
        :throws: :php:exc:`D3strukt0r\\Votifier\\Client\\Exception\\Socket\\NoConnectionException`
        
            If connection couldn't be established
            
        :throws: :php:exc:`D3strukt0r\\Votifier\\Client\\Exception\\Socket\\PackageNotReceivedException`
            
            If there was an error receiving the package

        :throws: :php:exc:`D3strukt0r\\Votifier\\Client\\Exception\\NotVotifierException`
        
            If the server we are connected to is not a valid Votifier server

    .. php:method:: public sendVote(...$votes)

        Sends the vote packages to the server.

        :param D3strukt0r\\Votifier\\Client\\Vote\\VoteInterface $votes: The vote packages

        :throws: :php:exc:`InvalidArgumentException`
            
            If one required parameter wasn't set
        
        :throws: :php:exc:`D3strukt0r\\Votifier\\Client\\Exception\\Socket\\NoConnectionException`
        
            If connection couldn't be established
            
        :throws: :php:exc:`D3strukt0r\\Votifier\\Client\\Exception\\Socket\\PackageNotReceivedException`
            
            If there was an error receiving the package
            
        :throws: :php:exc:`D3strukt0r\\Votifier\\Client\\Exception\\Socket\\PackageNotSentException`
            
            If there was an error sending the package

        :throws: :php:exc:`D3strukt0r\\Votifier\\Client\\Exception\\NotVotifierException`
        
            If the server we are connected to is not a valid Votifier server
