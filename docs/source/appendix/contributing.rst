.. Pulled from the central Team-MaRo/.github repo (the docs/source/_community
   submodule) so it is never duplicated or out of date here.

.. include:: ../_community/CONTRIBUTING.md
   :parser: myst_parser.docutils_

Code style guidelines
=====================

This project enforces two complementary code-style tools, both run by CI — make
sure both pass before opening a pull request.

**PHP_CodeSniffer** checks PSR-12 and PHP version compatibility (configured in
``.phpcs.xml``):

.. code-block:: bash

    composer check    # report problems  (alias for: ./vendor/bin/phpcs)
    composer fix      # auto-fix them     (alias for: ./vendor/bin/phpcbf)

**PHP-CS-Fixer** applies the project's coding standard (configured in
``.php-cs-fixer.dist.php``):

.. code-block:: bash

    composer cs       # show the diff without changing files
    composer cs:fix   # apply the fixes
