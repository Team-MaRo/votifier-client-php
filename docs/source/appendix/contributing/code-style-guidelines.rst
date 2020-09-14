=====================
Code style guidelines
=====================

This project has CodeSniffer as a dependency. So before creating a pull-request, make sure it doesn't show any warnings. The CI will test this too. You can try in two ways.

.. code-block:: bash

    ./vendor/bin/phpcs

.. code-block:: bash

    composer run-script check

And to fix it with :code:`phpcbf` you can run it with

.. code-block:: bash

    ./vendor/bin/phpcbf

.. code-block:: bash

    composer run-script fix

PHP-CS-Fixer is also installed, but because it's impossible to set it up to support PSR-12, I have removed it from being a requirement and left it as just "for information". You can try it anyways.

.. code-block:: bash

    ./vendor/bin/php-cs-fixer

.. code-block:: bash

    composer run-script check2
