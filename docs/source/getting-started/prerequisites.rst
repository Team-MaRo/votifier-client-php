=============
Prerequisites
=============

What things you need to install the software and how to install them

**PHP project using at least PHP 7.1)**

You could for example add the requirement to the composer.json file.

.. code-block:: json

    {
        "require": {
            "php": ">=7.1"
        }
    }

And then run

.. code-block:: bash

    composer require d3strukt0r/votifier-client

Or add it manually to the composer.json file

.. code-block:: json

    {
        "require": {
            "php": ">=7.1",
            "d3strukt0r/votifier-client": "^2"
        }
    }

**Minecraft server with the Votifier plugin**

You can set this up to be able to test if you website is set up correctly.

We can use a Docker container for this

.. code-block:: bash

    docker run -it \
               -p 25565:25565 \
               -p 8192:8192 \
               -v $(pwd)/data:/data \
               -e JAVA_MAX_MEMORY=1G \
               -e EULA=true \
               d3strukt0r/spigot

And place the latest and desired Votifier Jar that you want. Out of simplicity let's use the classic one.

https://dev.bukkit.org/projects/votifier/files/latest

And put it in :code:`./data/plugins/`

After that you can restart the server with the previous command.

Now you have your project with the plugin and a server which runs the votifier plugin.
