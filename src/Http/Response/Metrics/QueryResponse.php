<?php

/*
 * This file is part of the DataDogApi package.
 * (c) Romain Norberg <romainnorberg@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Romainnorberg\DataDogApi\Http\Response\Metrics;

class QueryResponse
{
    /**
     * @var \Romainnorberg\DataDogApi\Model\Metrics\Serie[]
     */
    public array $series = [];
}
