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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FormController extends AbstractController
{
    public function __invoke(Request $request): Response
    {
        return $this->render('form.php', [
            'success' => $request->get('success'),
            'error' => $request->get('error'),
        ]);
    }
}
