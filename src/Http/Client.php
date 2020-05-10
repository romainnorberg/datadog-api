<?php

/*
 * This file is part of the DataDogApi package.
 * (c) Romain Norberg <romainnorberg@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Romainnorberg\DataDogApi\Http;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Client implements ClientInterface
{
    private HttpClientInterface $httpclient;

    public function __construct(
        string $apiKey,
        string $applicationKey
    ) {
        $this->httpclient = HttpClient::create(
            [
                'headers' => [
                    'DD-API-KEY' => $apiKey,
                    'DD-APPLICATION-KEY' => $applicationKey,
                ],
            ]
        );
    }

    public function request(string $method, string $url, array $options): array
    {
        $response = $this->httpclient->request($method, $url, $options);

        return $response->toArray();
    }
}
