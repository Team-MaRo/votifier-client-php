==========================
Sending a vote to Votifier
==========================

When you have a server which runs the classic Votifier plugin, you can use this code here.

You can place following code wherever you want to set up the vote:

.. code-block:: php

    <?php

    use D3strukt0r\Votifier\Client\Server\Votifier;

    $server = (new Votifier())
        ->setHost('127.0.0.1')
        ->setPublicKey('MIIBIjANBgkq...')
    ;

And then to send it to the server:

.. code-block:: php

    <?php

    use D3strukt0r\Votifier\Client\Exception\NotVotifierException;
    use D3strukt0r\Votifier\Client\Exception\Socket\NoConnectionException;
    use D3strukt0r\Votifier\Client\Exception\Socket\PackageNotReceivedException;
    use D3strukt0r\Votifier\Client\Exception\Socket\PackageNotSentException;
    use D3strukt0r\Votifier\Client\Server\Votifier;
    use D3strukt0r\Votifier\Client\Vote\ClassicVote;

    $vote = (new ClassicVote())
        ->setUsername($_GET['username'])
        ->setServiceName('Your vote list')
        ->setAddress($_SERVER['REMOTE_ADDR'])
    ;

    try {
        /** @var Votifier $server */
        $server->sendVote($vote);
        // Connection created, and vote sent.
        // Doesn't mean the server handled it correctly, but the client did.
    } catch (InvalidArgumentException $e) {
        // Not all variables that are needed have been set.
        // See $e->getMessage() for all errors.
    } catch (NoConnectionException $e) {
        // Could not create a connection (socket) to the specified server
    } catch (PackageNotReceivedException $e) {
        // If the package couldn't be received, for whatever reason.
    } catch (PackageNotSentException $e) {
        // If the package couldn't be send, for whatever reason.
    }

Send multiple votes
===================

If you want you can also pass multiple votes, for when you have set up something like a scheduler.

.. code-block:: php

    <?php

    use D3strukt0r\Votifier\Client\Server\Votifier;
    use D3strukt0r\Votifier\Client\Vote\ClassicVote;

    /** @var Votifier $server */
    /** @var ClassicVote $vote1 */
    /** @var ClassicVote $vote2 */
    /** @var ClassicVote $vote3 */
    $server->sendVote($vote1, $vote2, $vote3);

To have an in-depth look at the classes and their objects, refer to the API section.

Full example
============

The following code is another example of a full HTML page with the code from above

.. code-block:: php

    <?php

    require __DIR__ . '/vendor/autoload.php';

    use D3strukt0r\Votifier\Client\Exception\NotVotifierException;
    use D3strukt0r\Votifier\Client\Exception\Socket\NoConnectionException;
    use D3strukt0r\Votifier\Client\Exception\Socket\PackageNotReceivedException;
    use D3strukt0r\Votifier\Client\Exception\Socket\PackageNotSentException;
    use D3strukt0r\Votifier\Client\Server\Votifier;
    use D3strukt0r\Votifier\Client\Vote\ClassicVote;

    if (isset($_GET['username'])) {
        $server = (new Votifier())
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
