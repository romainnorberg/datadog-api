<?php

/*
 * This file is part of the DataDogApi package.
 * (c) Romain Norberg <romainnorberg@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Romainnorberg\DataDogApi\Services\Downtime;

use Romainnorberg\DataDogApi\Http\ClientInterface;
use Romainnorberg\DataDogApi\Http\Response\Downtimes\DowntimeResponse;
use Romainnorberg\DataDogApi\Services\Service;

class Downtimes implements Service
{
    private ClientInterface $client;
    private bool $currentOnly = false;
    private string $response;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function handle(): self
    {
        $query = [];

        if (true === $this->currentOnly) {
            $query['current_only'] = true;
        }

        $this->response = $this->client->request('GET',
            'downtime',
            [
                'query' => $query,
            ]);

        return $this;
    }

    public function response(): array
    {
        $mapper = new \JsonMapper();

        return $mapper->mapArray(json_decode($this->response, false, 512, \JSON_THROW_ON_ERROR), [], DowntimeResponse::class);
    }

    public function currentOnly(): self
    {
        $this->currentOnly = true;

        return $this;
    }
}
