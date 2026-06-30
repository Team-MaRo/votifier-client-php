<?php
// @php-cs-fixer-ignore phpdoc_to_comment
use Symfony\Component\Templating\PhpEngine;

/** @var PhpEngine $view */ ?>
<?php $view->extend('base.php'); ?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-bold">Votifier GUI Tester</h1>
    <?php $view['slots']->output('_content'); ?>
</div>
