<?php declare(strict_types=1);

/**
 * Votifier PHP Client
 *
 * @package   Votifier Client
 * @author    Manuele Vaccari <dev@d3strukt0r.dev>
 * @copyright Copyright (c) 2015-2020, 2026 Manuele Vaccari <dev@d3strukt0r.dev>
 * @license   https://github.com/Team-MaRo/votifier-client-php/blob/master/LICENSE.txt MIT License
 * @link      https://github.com/Team-MaRo/votifier-client-php
 */

require_once __DIR__.'/vendor/autoload.php';

use IWFWeb\CodingStandard\IWFWebStandardRiskySet;
use IWFWeb\CodingStandard\IWFWebStandardSet;
use PhpCsFixer\Config;
use PhpCsFixer\Finder;

preg_match(
    '/Copyright \(c\) ([0-9][0-9,\s-]*[0-9])\s+(.+)/',
    (string) file_get_contents(__DIR__.'/LICENSE.txt'),
    $copyright,
);
[, $years, $name] = $copyright;
$email = json_decode((string) file_get_contents(__DIR__.'/composer.json'), true)['authors'][0]['email'];

$header = <<<EOF
    Votifier PHP Client

    @package   Votifier Client
    @author    {$name} <{$email}>
    @copyright Copyright (c) {$years} {$name} <{$email}>
    @license   https://github.com/Team-MaRo/votifier-client-php/blob/master/LICENSE.txt MIT License
    @link      https://github.com/Team-MaRo/votifier-client-php
    EOF;

// https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/master/doc/ruleSets/index.rst
// https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/master/doc/rules/index.rst
return (new Config())
    ->registerCustomRuleSets([
        new IWFWebStandardSet(),
        new IWFWebStandardRiskySet(),
    ])
    ->setFinder(Finder::create()
        ->in(__DIR__)
        ->ignoreDotFiles(false)
        ->ignoreVCSIgnored(true),
    )
    ->setRiskyAllowed(true)
    ->setRules([
        '@IWFWeb/standard' => true,
        '@IWFWeb/standard:risky' => true,
        'header_comment' => [
            'comment_type' => 'PHPDoc',
            'header' => $header,
        ],
        // The library targets PHP 7.1, so only arrays may carry a trailing comma
        // in multiline (valid since PHP 5). Trailing commas in function calls
        // (PHP 7.3+) and declarations (PHP 8.0+) would break parsing on 7.1.
        'trailing_comma_in_multiline' => ['elements' => ['arrays']],
    ])
;
