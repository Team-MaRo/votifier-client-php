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

And place the latest and desired Votifier Jar that you want. Out of simplicity let's use the classic one.

https://dev.bukkit.org/projects/votifier/files/latest

And put it in :code:`./spigot/plugins/`

After that you can restart the server with the previous command.

Now you have your project with the plugin and a server which runs the votifier plugin.
