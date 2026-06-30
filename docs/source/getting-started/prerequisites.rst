=============
Prerequisites
=============

This PHP library will need to be running with **at least PHP version 7.1** or higher. You can make sure about this by adding following to your :code:`composer.json` file (:code:`composer require php` doesn't work).

.. code-block:: json

    {
        "require": {
            "php": ">=7.1"
        }
    }

With that requirement set, you can now add the library itself.

.. code-block:: bash

    composer require d3strukt0r/votifier-client

**Minecraft server with the Votifier plugin**

You can set this up to be able to test if you website is set up correctly.

We can use a Docker container for this

.. code-block:: bash

    docker run \
        -it \
        -p 25565:25565 \
        -v $(pwd)/spigot:/app \
        -e JAVA_MAX_MEMORY=1G \
        -e EULA=true \
        d3strukt0r/spigot

Place a Votifier plugin jar in :code:`./spigot/plugins/` and restart the server.

We recommend **NuVotifier** -- it is maintained, runs on current Java, and speaks both the
classic Votifier protocol and its own v2 token protocol:

- SpigotMC: https://www.spigotmc.org/resources/nuvotifier.13449/
- GitHub releases: https://github.com/NuVotifier/NuVotifier/releases

.. warning::

   Avoid the original "classic" vexsoftware Votifier on modern servers: it relies on
   ``javax.xml.bind.DatatypeConverter`` (removed from the JDK in Java 11), so it only runs
   on Java 8 -- i.e. Minecraft up to 1.16.5. See :doc:`votifier` for the details. For
   reference: https://github.com/vexsoftware/votifier and
   https://www.curseforge.com/minecraft/bukkit-plugins/votifier

Now you have your project with the plugin and a server which runs the votifier plugin.
