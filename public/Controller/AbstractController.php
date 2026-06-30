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

use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Exception\SessionNotFoundException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class AbstractController
{
    /**
     * @var null|ContainerInterface
     */
    protected $container;

    /**
     * @required
     */
    public function setContainer(ContainerInterface $container): ?ContainerInterface
    {
        $previous = $this->container;
        $this->container = $container;

        return $previous;
    }

    protected function generateUrl(string $route, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH): string
    {
        return $this->container->get('router')->generate($route, $parameters, $referenceType);
    }

    protected function redirect(string $url, int $status = 302): RedirectResponse
    {
        return new RedirectResponse($url, $status);
    }

    protected function redirectToRoute(string $route, array $parameters = [], int $status = 302): RedirectResponse
    {
        return $this->redirect($this->generateUrl($route, $parameters), $status);
    }

    protected function addFlash(string $type, $message): void
    {
        try {
            $this->container->get('request_stack')->getSession()->getFlashBag()->add($type, $message);
        } catch (SessionNotFoundException $e) {
            throw new \LogicException('You cannot use the addFlash method if sessions are disabled. Enable them in "config/packages/framework.yaml".', 0, $e);
        }
    }

    protected function renderView(string $view, array $parameters = []): string
    {
        return $this->container->get('templating')->render($view, $parameters);
    }

    protected function render(string $view, array $parameters = [], ?Response $response = null): Response
    {
        $content = $this->renderView($view, $parameters);

        if ($response === null) {
            $response = new Response();
        }

        $response->setContent($content);

        return $response;
    }
}
