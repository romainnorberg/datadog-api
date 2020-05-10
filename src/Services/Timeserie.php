<?php

/*
 * This file is part of the DataDogApi package.
 * (c) Romain Norberg <romainnorberg@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Romainnorberg\DataDogApi\Services;

use Romainnorberg\DataDogApi\Http\ClientInterface;
use Romainnorberg\DataDogApi\Middlewares\Period;
use Romainnorberg\DataDogApi\Models\Point;

class Timeserie implements Service
{
    private ClientInterface $client;
    private \DateTimeInterface $from;
    private \DateTimeInterface $to;
    private string $query;
    private array $response;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function from(\DateTimeInterface $from): self
    {
        $this->from = $from;

        return $this;
    }

    public function to(\DateTimeInterface $to): self
    {
        $this->to = $to;

        return $this;
    }

    public function query(string $query): self
    {
        $this->query = $query;

        return $this;
    }

    public function handle(): self
    {
        $period = Period::create($this->from, $this->to);

        $this->response = $this->client->request('GET',
            'https://api.datadoghq.com/api/v1/query',
            [
                'query' => [
                    'from' => $period->from->format('U'),
                    'to' => $period->to->format('U'),
                    'query' => $this->query,
                ],
            ]);

        return $this;
    }

    public function response()
    {
        return $this->response;
    }

    public function maxPoint()
    {
        $pointList = $this->response['series'][0]['pointlist'];

        usort($pointList, static function ($a, $b) {
            return $a[1] <=> $b[1];
        });

        $pointList = end($pointList);

        $point = new Point();
        $point->timestamp = $pointList[0];
        $point->value = $pointList[1];

        return $point;
    }
}
