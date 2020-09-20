============================
Sending a vote to NuVotifier
============================

When you have a server which runs the NuVotifier plugin, you can use this code here.

For NuVotifier however, there are two ways of sending a vote package. Either the classic version, which is
NOT the same, like using the ClassicVotifier approach, or the version 2 which is special to NuVotifier.

The following is the classic one, which looks the same as Votifier:

.. code-block:: php

    <?php

    use D3strukt0r\Votifier\Client\Server\NuVotifier;
    use D3strukt0r\Votifier\Client\Vote\ClassicVote;

    $server = (new NuVotifier())
        ->setHost('127.0.0.1')
        ->setPublicKey('MIIBIjANBgkq...')
    ;
    $vote = (new ClassicVote())
        ->setUsername($_GET['username'])
        ->setServiceName('Your vote list')
        ->setAddress($_SERVER['REMOTE_ADDR'])
    ;

Or with version 2 protocol:

.. code-block:: php

    <?php

    use D3strukt0r\Votifier\Client\Server\NuVotifier;
    use D3strukt0r\Votifier\Client\Vote\ClassicVote;

    $server = (new NuVotifier())
        ->setHost('127.0.0.1')
        ->setProtocolV2(true)
        ->setToken('7j302r4n...')
    ;
    $vote = (new ClassicVote())
        ->setUsername($_GET['username'])
        ->setServiceName('Your vote list')
        ->setAddress($_SERVER['REMOTE_ADDR'])
    ;

And then you can send it the same way to the server:

.. code-block:: php

    <?php

    use D3strukt0r\Votifier\Client\Exception\NotVotifierException;
    use D3strukt0r\Votifier\Client\Exception\NuVotifierChallengeInvalidException;
    use D3strukt0r\Votifier\Client\Exception\NuVotifierException;
    use D3strukt0r\Votifier\Client\Exception\NuVotifierSignatureInvalidException;
    use D3strukt0r\Votifier\Client\Exception\NuVotifierUnknownServiceException;
    use D3strukt0r\Votifier\Client\Exception\NuVotifierUsernameTooLongException;
    use D3strukt0r\Votifier\Client\Exception\Socket\NoConnectionException;
    use D3strukt0r\Votifier\Client\Exception\Socket\PackageNotReceivedException;
    use D3strukt0r\Votifier\Client\Exception\Socket\PackageNotSentException;
    use D3strukt0r\Votifier\Client\Server\ServerInterface;
    use D3strukt0r\Votifier\Client\Vote\VoteInterface;

    try {
        /** @var ServerInterface $server */
        /** @var VoteInterface $vote */
        $server->sendVote($vote);
        // Connection created, and vote sent. Doesn't mean the server handled it correctly, but the client did.
    } catch (InvalidArgumentException $e) {
        // Not all variables that are needed have been set. See $e->getMessage() for all errors.
    } catch (NoConnectionException $e) {
        // Could not create a connection (socket) to the specified server
    } catch (PackageNotReceivedException $e) {
        // If the package couldn't be received, for whatever reason.
    } catch (PackageNotSentException $e) {
        // If the package couldn't be send, for whatever reason.
    } catch (NotVotifierException $e) {
        // The server didn't give a standard Votifier response
    } catch (NuVotifierChallengeInvalidException $e) {
        // Specific for NuVotifier: The challenge was invalid (Shouldn't happen by default, but it's here in case.
    } catch (NuVotifierSignatureInvalidException $e) {
        // Specific for NuVotifier: The signature was invalid (Shouldn't happen by default, but it's here in case.
    } catch (NuVotifierUnknownServiceException $e) {
        // Specific for NuVotifier: A token can be specific for a list, so if the list isn't supposed to use the given token, this message appears.
    } catch (NuVotifierUsernameTooLongException $e) {
        // Specific for NuVotifier: A username cannot be over 16 characters (Why? Don't ask me)
    } catch (NuVotifierException $e) {
        // In case there is a new error message that wasn't added to the library, this will take care of that.
    } catch (Exception $exception) {
        // This should never be thrown, but just in case.
    }

To have an in-depth look at the classes and their objects, refer to the API section.

Full example with v1
====================

The following code is another example of a full HTML page with the code from above

.. code-block:: php

    <?php

    require __DIR__ . '/vendor/autoload.php';

    use D3strukt0r\VotifierClient\ServerType\NuVotifier;
    use D3strukt0r\VotifierClient\Vote;
    use D3strukt0r\VotifierClient\VoteType\ClassicVote;

    if (isset($_GET['username'])) {
        $server = (new NuVotifier())
            ->setHost('127.0.0.1')
            ->setPublicKey('MIIBIjANBgkq...')
        ;
        $vote = (new ClassicVote())
            ->setUsername($_GET['username'])
            ->setServiceName('Your vote list')
            ->setAddress($_SERVER['REMOTE_ADDR'])
        ;

        try {
            $server->sendVote($vote);
            echo "<p>Connection created, and vote sent. Doesn't mean the server handled it correctly, but the client did.</p>";
        } catch (InvalidArgumentException $e) {
            echo "<p>Not all variables that are needed have been set. See $e->getMessage() for all errors.</p>";
        } catch (NoConnectionException $e) {
            echo "<p>Could not create a connection (socket) to the specified server</p>";
        } catch (PackageNotReceivedException $e) {
            echo "<p>If the package couldn't be received, for whatever reason.</p>";
        } catch (PackageNotSentException $e) {
            echo "<p>If the package couldn't be send, for whatever reason.</p>";
        } catch (NotVotifierException $e) {
            echo "<p>The server didn't give a standard Votifier response</p>";
        } catch (NuVotifierChallengeInvalidException $e) {
            echo "<p>Specific for NuVotifier: The challenge was invalid (Shouldn't happen by default, but it's here in case).</p>";
        } catch (NuVotifierSignatureInvalidException $e) {
            echo "<p>Specific for NuVotifier: The signature was invalid (Shouldn't happen by default, but it's here in case).</p>";
        } catch (NuVotifierUnknownServiceException $e) {
            echo "<p>Specific for NuVotifier: A token can be specific for a list, so if the list isn't supposed to use the given token, this message appears.</p>";
        } catch (NuVotifierUsernameTooLongException $e) {
            echo "<p>Specific for NuVotifier: A username cannot be over 16 characters (Why? Don't ask me)</p>";
        } catch (NuVotifierException $e) {
            echo "<p>In case there is a new error message that wasn't added to the library, this will take care of that.</p>";
        } catch (Exception $e) {
            echo "<p>This should never be thrown, but just in case.</p>";
        }
    }

    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <title>Votifier</title>
        </head>
        <body>
            <form>
                <label for="username">Username:</label><br>
                <input type="text" id="username" name="username">
            </form>
        </body>
    </html>

Full example with v2
====================

The following code is another example of a full HTML page with the code from above

.. code-block:: php

    <?php

    require __DIR__ . '/vendor/autoload.php';

    use D3strukt0r\VotifierClient\ServerType\NuVotifier;
    use D3strukt0r\VotifierClient\Vote;
    use D3strukt0r\VotifierClient\VoteType\ClassicVote;

    if (isset($_GET['username'])) {
        $server = (new NuVotifier())
            ->setHost('127.0.0.1')
            ->setProtocolV2(true)
            ->setToken('7j302r4n...')
        ;
        $vote = (new ClassicVote())
            ->setUsername($_GET['username'])
            ->setServiceName('Your vote list')
            ->setAddress($_SERVER['REMOTE_ADDR'])
        ;

        try {
            $server->sendVote($vote);
            echo "<p>Connection created, and vote sent. Doesn't mean the server handled it correctly, but the client did.</p>";
        } catch (InvalidArgumentException $e) {
            echo "<p>Not all variables that are needed have been set. See $e->getMessage() for all errors.</p>";
        } catch (NoConnectionException $e) {
            echo "<p>Could not create a connection (socket) to the specified server</p>";
        } catch (PackageNotReceivedException $e) {
            echo "<p>If the package couldn't be received, for whatever reason.</p>";
        } catch (PackageNotSentException $e) {
            echo "<p>If the package couldn't be send, for whatever reason.</p>";
        } catch (NotVotifierException $e) {
            echo "<p>The server didn't give a standard Votifier response</p>";
        } catch (NuVotifierChallengeInvalidException $e) {
            echo "<p>Specific for NuVotifier: The challenge was invalid (Shouldn't happen by default, but it's here in case).</p>";
        } catch (NuVotifierSignatureInvalidException $e) {
            echo "<p>Specific for NuVotifier: The signature was invalid (Shouldn't happen by default, but it's here in case).</p>";
        } catch (NuVotifierUnknownServiceException $e) {
            echo "<p>Specific for NuVotifier: A token can be specific for a list, so if the list isn't supposed to use the given token, this message appears.</p>";
        } catch (NuVotifierUsernameTooLongException $e) {
            echo "<p>Specific for NuVotifier: A username cannot be over 16 characters (Why? Don't ask me)</p>";
        } catch (NuVotifierException $e) {
            echo "<p>In case there is a new error message that wasn't added to the library, this will take care of that.</p>";
        } catch (Exception $e) {
            echo "<p>This should never be thrown, but just in case.</p>";
        }
    }

    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <title>Votifier</title>
        </head>
        <body>
            <form>
                <label for="username">Username:</label><br>
                <input type="text" id="username" name="username">
            </form>
        </body>
    </html>
