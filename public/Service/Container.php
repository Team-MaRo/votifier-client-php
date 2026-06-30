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

use App\Exception\NotFoundException;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    /**
     * @var array
     */
    private $definitions = [];

    /**
     * @var array
     */
    private $resolvedEntries = [];

    public function __construct(array $definitions)
    {
        $this->definitions = array_merge($definitions, [ContainerInterface::class => $this]);
    }

    /**
     * @return mixed
     */
    public function get(string $id)
    {
        if (!$this->has($id)) {
            throw new NotFoundException("No entry or class found for '{$id}'");
        }

        if (\array_key_exists($id, $this->resolvedEntries)) {
            return $this->resolvedEntries[$id];
        }

        $value = $this->definitions[$id];
        if ($value instanceof \Closure) {
            $value = $value($this);
        }

        $this->resolvedEntries[$id] = $value;

        return $value;
    }

    public function has(string $id): bool
    {
        return \array_key_exists($id, $this->definitions) || \array_key_exists($id, $this->resolvedEntries);
    }
}
