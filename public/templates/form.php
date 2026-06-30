<?php
// @php-cs-fixer-ignore phpdoc_to_comment
use Symfony\Component\Templating\PhpEngine;

/** @var PhpEngine $view */ ?>
<?php $view->extend('layout.php'); ?>

<?php if (!empty($success)) { ?>
    <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
        <?php echo $view->escape($success); ?>
    </div>
<?php } ?>
<?php if (!empty($error)) { ?>
    <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
        <?php echo $view->escape($error); ?>
    </div>
<?php } ?>

<h2 class="text-3xl font-bold mt-16">Votifier</h2>
<form action="<?php echo $view['router']->path('votifier'); ?>">
    <label for="username_votifier">Username:</label><br>
    <input type="text" name="username" id="username_votifier" class="form-input">
    <button type="submit" class="btn">Send</button>
</form>
<h2 class="text-3xl font-bold mt-16">NuVotifier</h2>
<form action="<?php echo $view['router']->path('nuvotifier'); ?>">
    <label for="username_nuvotifier">Username:</label><br>
    <input type="text" name="username" id="username_nuvotifier" class="form-input">
    <button type="submit" class="btn">Send</button>
</form>
