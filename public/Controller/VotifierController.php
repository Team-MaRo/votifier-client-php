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

use D3strukt0r\Votifier\Client\Exception\Socket\NoConnectionException;
use D3strukt0r\Votifier\Client\Exception\Socket\PackageNotReceivedException;
use D3strukt0r\Votifier\Client\Exception\Socket\PackageNotSentException;
use D3strukt0r\Votifier\Client\Server\Votifier;
use D3strukt0r\Votifier\Client\Vote\ClassicVote;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class VotifierController extends AbstractController
{
    public function __invoke(Request $request): Response
    {
        $publicKeyFile = __DIR__.'/../../server/plugins/Votifier/rsa/public.key';
        if (!is_file($publicKeyFile)) {
            return $this->redirectToRoute('index', ['error' => 'Votifier public key not found at server/plugins/Votifier/rsa/public.key — start the server with the Votifier plugin first.']);
        }

        $server = (new Votifier())
            ->setHost(getenv('VOTIFIER_HOST') ?: '127.0.0.1')
            ->setPublicKey(file_get_contents($publicKeyFile))
        ;
        $vote = (new ClassicVote())
            ->setUsername($request->get('username'))
            ->setServiceName('Your vote list')
            ->setAddress($request->server->get('REMOTE_ADDR'))
        ;

        try {
            $server->sendVote($vote);

            return $this->redirectToRoute('index', ['success' => 'Connection created, and vote sent. Doesn\'t mean the server handled it correctly, but the client did.']);
        } catch (\InvalidArgumentException $e) {
            return $this->redirectToRoute('index', ['error' => "Not all variables that are needed have been set. See {$e->getMessage()} for all errors."]);
        } catch (NoConnectionException $e) {
            return $this->redirectToRoute('index', ['error' => "Could not create a connection (socket) to the specified server: {$e->getMessage()}"]);
        } catch (PackageNotReceivedException $e) {
            return $this->redirectToRoute('index', ['error' => "Could not receive a response from the server: {$e->getMessage()}"]);
        } catch (PackageNotSentException $e) {
            return $this->redirectToRoute('index', ['error' => "If the package couldn't be send, for whatever reason."]);
        }
    }
}
