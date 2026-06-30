<?php
// @php-cs-fixer-ignore phpdoc_to_comment
use Symfony\Component\Templating\PhpEngine;

/** @var PhpEngine $view */ ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
        <style type="text/tailwindcss">
            @layer utilities {
                .btn {
                    @apply inline-block px-6 py-2 border-2 border-blue-600 text-blue-600 font-medium text-xs leading-tight uppercase rounded hover:bg-black hover:bg-opacity-5 focus:outline-none focus:ring-0 transition duration-150 ease-in-out;
                }
            }
        </style>

        <title><?php $view['slots']->output('title', 'Vote'); ?></title>
    </head>
    <body>
        <?php $view['slots']->output('_content'); ?>
    </body>
</html>
