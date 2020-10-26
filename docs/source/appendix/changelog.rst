=========
Changelog
=========

3.0.0
=====
- :code:`Added` :code:`verifyConnection` function to server, to check the server separately. You can figure out if the server is from Votifier before sending a vote
- :code:`Added` php compatibility check for code style check
- :code:`Added` Added setter methods to servers and and votes in place of constructor
- :code:`Added` exceptions for all possible errors (instead of the old Messages class)
- :code:`Dropped` assigning variables in the constructor
- :code:`Dropped` :code:`Messages` class
- :code:`Moved` namespace to :code:`D3strukt0r\Votifier\Client\Server`
- :code:`Fixed` composer not ignoring unnecessary files
- :code:`Fixed` coverage reports
- :code:`Fixed` coding style
- :code:`Updated` the docs

2.1.2
=====
Nothing changed in the code, only improved CI and fixed Coverage report

2.1.1
=====
- :code:`Added` more documentation using "Read the Docs"
- :code:`Fixed` Bug `#1 <https://github.com/D3strukt0r/votifier-client-php/issues/1>`_

2.1.0
=====
- :code:`Added` some required PHP libraries
- :code:`Dropped` compatibility with depreciated PHP versions. Now PHP 7.1+ is required
- :code:`Fixed` Travis tests
- :code:`Fixed` coding style
- :code:`Updated` the docs

TODO
----
- :code:`Add` more tests

2.0.0
=====
- :code:`Added` server types (to use with different plugins) (Currently: Votifier/NuVotifier)
- :code:`Added` vote types, in case different package types are needed.
- :code:`Added` API Docs using phpDocumentor

Notes
-----
Anyone who thinks a new ServerType is needed can open an issue and I'll add it to a minor release.
I already tested Votifier/NuVotifier protocol v1/NuVotifier protocol v2 manually and it worked

1.0.0
=====
- :code:`Added` coveralls
- :code:`Removed` unnecessary code
- :code:`Fixed` code style

0.0.1-beta
==========
Begin project
