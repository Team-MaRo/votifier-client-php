<?php

/**
 * Votifier PHP Client
 *
 * @package   Votifier Client
 * @author    Manuele Vaccari <dev@d3strukt0r.dev>
 * @copyright Copyright (c) 2015-2020, 2026 Manuele Vaccari <dev@d3strukt0r.dev>
 * @license   https://github.com/Team-MaRo/votifier-client-php/blob/master/LICENSE.txt MIT License
 * @link      https://github.com/Team-MaRo/votifier-client-php
 */

namespace App\Templating;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Templating\Helper\Helper;

/**
 * Minimal "router" helper for the PHP templating engine, so templates can call
 * $view['router']->path('route_name'). The standalone Templating component has
 * no router helper (that lives in the framework bundle), so we provide one.
 */
final class RouterHelper extends Helper
{
    /**
     * @var UrlGeneratorInterface
     */
    private $generator;

    public function __construct(UrlGeneratorInterface $generator)
    {
        $this->generator = $generator;
    }

    public function path(string $name, array $parameters = []): string
    {
        return $this->generator->generate($name, $parameters, UrlGeneratorInterface::ABSOLUTE_PATH);
    }

    public function url(string $name, array $parameters = []): string
    {
        return $this->generator->generate($name, $parameters, UrlGeneratorInterface::ABSOLUTE_URL);
    }

    public function getName(): string
    {
        return 'router';
    }
}
