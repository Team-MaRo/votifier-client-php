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

namespace App\Service;

use App\Controller\AbstractController;
use Symfony\Component\HttpKernel\Controller\ContainerControllerResolver;

class ControllerResolver extends ContainerControllerResolver
{
    protected function instantiateController(string $class): object
    {
        $controller = parent::instantiateController($class);

        if ($controller instanceof AbstractController) {
            $controller->setContainer($this->container);
        }

        return $controller;
    }
}
