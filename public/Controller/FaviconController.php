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

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

/**
 * Browsers auto-request /favicon.ico; without a route the kernel throws
 * NotFoundHttpException and floods the log. Answer it with an empty 404.
 */
class FaviconController extends AbstractController
{
    public function __invoke(): Response
    {
        return new Response('', Response::HTTP_NOT_FOUND);
    }
}
