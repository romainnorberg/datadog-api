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
use Romainnorberg\DataDogApi\Http\Response\Metrics\Point;
use Romainnorberg\DataDogApi\Middlewares\Period;
use Romainnorberg\DataDogApi\Services\Service;

class Query implements Service
{
    private ClientInterface $client;
    private DateTimeInterface $from;
    private DateTimeInterface $to;
    private string $query;
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

    public function to(DateTimeInterface $to): self
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
            'query',
            [
                'query' => [
                    'from'  => $period->from->format('U'),
                    'to'    => $period->to->format('U'),
                    'query' => $this->query,
                ],
            ]);

        return $this;
    }

    public function response(): \Romainnorberg\DataDogApi\Http\Response\Metrics\Query
    {
        $mapper = new JsonMapper();

        return $mapper->map(json_decode($this->response, false, 512, JSON_THROW_ON_ERROR), new \Romainnorberg\DataDogApi\Http\Response\Metrics\Query());
    }

    public function maxPointBySerie(): array
    {
        $maxBySerie = [];
        foreach ($this->response()->series as $serie) {
            $pointList = $serie->pointlist;

            usort($pointList, static function ($a, $b) {
                return $a[1] <=> $b[1];
            });

            $maxBySerie[] = [
                'serie' => $serie->withoutPointList(),
                'point' => end($pointList),
            ];
        }

        return $maxBySerie;
    }

    public function maxPoint(): ?Point
    {
        $maxBySerie = $this->maxPointBySerie();

        if (empty($maxBySerie)) {
            return null;
        }

        usort($maxBySerie, static function ($a, $b) {
            return $a['point'][1] <=> $b['point'][1];
        });

        $maxBySerie = end($maxBySerie);

        $point = new Point();
        $point->timestamp = $maxBySerie['point'][0];
        $point->value = $maxBySerie['point'][1];

        return $point;
    }
}
