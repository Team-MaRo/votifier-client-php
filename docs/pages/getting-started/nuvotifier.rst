==============================
Using a server with NuVotifier
==============================

When you have a server which runs the NuVotifier plugin, you can use this code here.

For NuVotifier however, there are two ways of sending a vote package. Either the classic version, which is
EXACTLY the same, like using the ClassicVotifier approach, or the version 2 which is special to NuVotifier.

The following is the classic one, which is as mentioned, the same like ClassicVotifier:

.. code-block:: php

    <?php

    use D3strukt0r\VotifierClient\ServerType\NuVotifier;
    use D3strukt0r\VotifierClient\Vote;
    use D3strukt0r\VotifierClient\VoteType\ClassicVote;

    $serverType = new NuVotifier('127.0.0.1', null, 'MIIBIjANBgkq...');
    $voteType = new ClassicVote($_GET['username'], 'Your vote list', $_SERVER['REMOTE_ADDR']);
    $vote = new Vote($voteType, $serverType);

Or with version 2 protocol:

.. code-block:: php

    <?php

    use D3strukt0r\VotifierClient\ServerType\NuVotifier;
    use D3strukt0r\VotifierClient\Vote;
    use D3strukt0r\VotifierClient\VoteType\ClassicVote;

    $serverType = new NuVotifier('127.0.0.1', null, null, true, '7j302r4n...');
    $voteType = new ClassicVote($_GET['username'], 'Your vote list', $_SERVER['REMOTE_ADDR']);
    $vote = new Vote($voteType, $serverType);

And then you can send it the same way to the server:

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
