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

require __DIR__.'/vendor/autoload.php';

use App\Controller\FaviconController;
use App\Controller\FormController;
use App\Controller\NuVotifierController;
use App\Controller\VotifierController;
use App\Service\Container;
use App\Service\ControllerResolver;
use App\Templating\RouterHelper;
use Psr\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Templating\Helper\SlotsHelper;
use Symfony\Component\Templating\Loader\FilesystemLoader;
use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParser;

$routes = new RouteCollection();
$routes->add('index', new Route('/', ['_controller' => FormController::class]));
$routes->add('votifier', new Route('/votifier', ['_controller' => VotifierController::class]));
$routes->add('nuvotifier', new Route('/nuvotifier', ['_controller' => NuVotifierController::class]));
$routes->add('favicon', new Route('/favicon.ico', ['_controller' => FaviconController::class]));

$request = Request::createFromGlobals();

$context = new RequestContext();

// Routing can match routes with incoming requests
$matcher = new UrlMatcher($routes, $context);

$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber(new RouterListener($matcher, new RequestStack()));

$templating = null;
$requestStack = new RequestStack();
$generator = new UrlGenerator($routes, $context);
$controllerResolver = new ControllerResolver(new Container([
    'router' => $generator,
    'request_stack' => $requestStack,
    'templating' => static function (ContainerInterface $container) use (&$templating, &$generator) {
        if ($templating === null) {
            $filesystemLoader = new FilesystemLoader(__DIR__.'/templates/%name%');

            $templating = new PhpEngine(new TemplateNameParser(), $filesystemLoader);
            $templating->set(new SlotsHelper());
            $templating->set(new RouterHelper($generator));
        }

        return $templating;
    },
]));
$argumentResolver = new ArgumentResolver();

$kernel = new HttpKernel($dispatcher, $controllerResolver, $requestStack, $argumentResolver);

$response = $kernel->handle($request);
$response->send();

$kernel->terminate($request, $response);
