============================
Using a server with Votifier
============================

When you have a server which runs the classic Votifier plugin, you can use this code here.

You can place following code whereever you want to set up the vote:

.. code-block:: php

    <?php

    use D3strukt0r\VotifierClient\ServerType\ClassicVotifier;
    use D3strukt0r\VotifierClient\Vote;
    use D3strukt0r\VotifierClient\VoteType\ClassicVote;

    $serverType = new ClassicVotifier('127.0.0.1', null, 'MIIBIjANBgkq...');
    $voteType = new ClassicVote($_GET['username'], 'Your vote list', $_SERVER['REMOTE_ADDR']);
    $vote = new Vote($voteType, $serverType);

And then to send it to the server:

.. code-block:: php

    <?php

    try {
        $vote->send();
        // Connection created, and vote sent. Doesn't mean the server handled it correctly,
        // but the client did.
    } catch (\Exception $exception) {
        // Could not send Vote. Normally this happens when the client can't create a connection.
    }

To have an in-depth look at the classes and their objects, refer to the API section.
