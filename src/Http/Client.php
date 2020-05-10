<?php

/*
 * This file is part of the DataDogApi package.
 * (c) Romain Norberg <romainnorberg@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Romainnorberg\DataDogApi\Http;

use Romainnorberg\DataDogApi\Http\Request\Request;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Client implements ClientInterface
{
    public const BASE_URL = 'https://api.datadoghq.com/api/v1/';
    public const STATUS_PAGE = 'https://status.datadoghq.com';
    public const STATUS_TWITTER = 'https://twitter.com/datadogops';

    private HttpClientInterface $httpclient;

    public function __construct(
        string $apiKey,
        string $applicationKey
    ) {
        $this->httpclient = HttpClient::create(
            [
                'headers' => [
                    'DD-API-KEY'         => $apiKey,
                    'DD-APPLICATION-KEY' => $applicationKey,
                ],
                'base_uri' => self::BASE_URL,
            ]
        );
    }

    public function request(string $method, string $url, array $options): string
    {
        $request = new Request($this->httpclient);

        return $request->request($method, $url, $options);
    }
}
