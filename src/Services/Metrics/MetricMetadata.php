<?php

/*
 * This file is part of the DataDogApi package.
 * (c) Romain Norberg <romainnorberg@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Romainnorberg\DataDogApi\Services\Metrics;

use JsonMapper;
use Romainnorberg\DataDogApi\Http\ClientInterface;
use Romainnorberg\DataDogApi\Services\Service;

class MetricMetadata implements Service
{
    private ClientInterface $client;
    private string $metricName;
    private string $response;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function metricName(string $metricName): self
    {
        $this->metricName = $metricName;

        return $this;
    }

    public function handle(): self
    {
        $this->response = $this->client->request('GET', sprintf('metrics/%s', $this->metricName), []);

        return $this;
    }

    public function response(): \Romainnorberg\DataDogApi\Http\Response\Metrics\MetricMetadataResponse
    {
        $mapper = new JsonMapper();

        return $mapper->map(json_decode($this->response, false, 512, JSON_THROW_ON_ERROR), new \Romainnorberg\DataDogApi\Http\Response\Metrics\MetricMetadataResponse());
    }
}
