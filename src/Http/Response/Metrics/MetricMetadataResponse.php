<?php

/*
 * This file is part of the DataDogApi package.
 * (c) Romain Norberg <romainnorberg@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Romainnorberg\DataDogApi\Http\Response\Metrics;

class MetricMetadataResponse
{
    /**
     * @var string|null
     */
    public $description;

    /**
     * @var string|null
     */
    public $short_name;

    /**
     * @var string|null
     */
    public $integration;

    /**
     * @var int|null
     */
    public $statsd_interval;

    /**
     * @var string|null
     */
    public $per_unit;

    /**
     * @var string|null
     */
    public $unit;

    public string $type;
}
