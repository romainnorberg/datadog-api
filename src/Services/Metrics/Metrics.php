<?php

/*
 * This file is part of the DataDogApi package.
 * (c) Romain Norberg <romainnorberg@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Romainnorberg\DataDogApi\Services\Metrics;

use DateTimeInterface;
use JsonMapper;
use Romainnorberg\DataDogApi\Http\ClientInterface;
use Romainnorberg\DataDogApi\Services\Service;

class Metrics implements Service
{
    private ClientInterface $client;
    private DateTimeInterface $from;
    private ?string $host = null;
    private string $response;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function from(DateTimeInterface $from): self
    {
        $this->from = $from;

        return $this;
    }

    public function host(string $host): self
    {
        $this->host = $host;

        return $this;
    }

    public function handle(): self
    {
        $this->response = $this->client->request('GET',
            'metrics',
            [
                'query' => [
                    'from' => $this->from->format('U'),
                    'host' => $this->host,
                ],
            ]);

        return $this;
    }

    public function response(): \Romainnorberg\DataDogApi\Http\Response\Metrics\Metrics
    {
        $mapper = new JsonMapper();

        return $mapper->map(json_decode($this->response, false, 512, JSON_THROW_ON_ERROR), new \Romainnorberg\DataDogApi\Http\Response\Metrics\Metrics());
    }

    public function hasMetric(string $metricName): bool
    {
        return \in_array($metricName, $this->response()->metrics, true);
    }
}
