<?php

/*
 * This file is part of the DataDogApi package.
 * (c) Romain Norberg <romainnorberg@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Romainnorberg\DataDogApi\Services;

use Romainnorberg\DataDogApi\Services\Downtime\Downtimes;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Downtime
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function list(): Downtimes
    {
        return $this->container->get(Downtimes::class);
    }
}
