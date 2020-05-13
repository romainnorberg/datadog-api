<?php

/*
 * This file is part of the DataDogApi package.
 * (c) Romain Norberg <romainnorberg@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Romainnorberg\DataDogApi\Services;

use Romainnorberg\DataDogApi\Services\Metrics\Query;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Metrics
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function query(): Query
    {
        return $this->container->get(Query::class);
    }

    public function list(): Metrics\Metrics
    {
        return $this->container->get(Metrics\Metrics::class);
    }
}
