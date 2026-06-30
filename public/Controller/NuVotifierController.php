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

use D3strukt0r\Votifier\Client\Exception\NotVotifierException;
use D3strukt0r\Votifier\Client\Exception\NuVotifierChallengeInvalidException;
use D3strukt0r\Votifier\Client\Exception\NuVotifierException;
use D3strukt0r\Votifier\Client\Exception\NuVotifierSignatureInvalidException;
use D3strukt0r\Votifier\Client\Exception\NuVotifierUnknownServiceException;
use D3strukt0r\Votifier\Client\Exception\NuVotifierUsernameTooLongException;
use D3strukt0r\Votifier\Client\Exception\Socket\NoConnectionException;
use D3strukt0r\Votifier\Client\Exception\Socket\PackageNotReceivedException;
use D3strukt0r\Votifier\Client\Exception\Socket\PackageNotSentException;
use D3strukt0r\Votifier\Client\Server\NuVotifier;
use D3strukt0r\Votifier\Client\Vote\ClassicVote;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Yaml;

class NuVotifierController extends AbstractController
{
    public function __invoke(Request $request): Response
    {
        $configFile = __DIR__.'/../../server/plugins/Votifier/config.yml';
        if (!is_file($configFile)) {
            return $this->redirectToRoute('index', ['error' => 'NuVotifier config not found at server/plugins/Votifier/config.yml — start the server with the NuVotifier plugin first.']);
        }
        $votifierConfig = Yaml::parseFile($configFile);

        $server = (new NuVotifier())
            ->setHost(getenv('VOTIFIER_HOST') ?: '127.0.0.1')
            ->setProtocolV2(true)
            ->setToken($votifierConfig['tokens']['default'])
        ;
        $vote = (new ClassicVote())
            ->setUsername($request->get('username'))
            ->setServiceName('Your vote list')
            ->setAddress($_SERVER['REMOTE_ADDR'])
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
        } catch (NotVotifierException $e) {
            return $this->redirectToRoute('index', ['error' => "The server didn't give a standard Votifier response"]);
        } catch (NuVotifierChallengeInvalidException $e) {
            return $this->redirectToRoute('index', ['error' => "Specific for NuVotifier: The challenge was invalid (Shouldn't happen by default, but it's here in case)."]);
        } catch (NuVotifierSignatureInvalidException $e) {
            return $this->redirectToRoute('index', ['error' => "Specific for NuVotifier: The signature was invalid (Shouldn't happen by default, but it's here in case)."]);
        } catch (NuVotifierUnknownServiceException $e) {
            return $this->redirectToRoute('index', ['error' => "Specific for NuVotifier: A token can be specific for a list, so if the list isn't supposed to use the given token, this message appears."]);
        } catch (NuVotifierUsernameTooLongException $e) {
            return $this->redirectToRoute('index', ['error' => "Specific for NuVotifier: A username cannot be over 16 characters (Why? Don't ask me)"]);
        } catch (NuVotifierException $e) {
            return $this->redirectToRoute('index', ['error' => "In case there is a new error message that wasn't added to the library, this will take care of that."]);
        }
    }
}
