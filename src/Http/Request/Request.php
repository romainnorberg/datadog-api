<?php

/*
 * This file is part of the DataDogApi package.
 * (c) Romain Norberg <romainnorberg@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Romainnorberg\DataDogApi\Http\Request;

use Romainnorberg\DataDogApi\Exceptions\InvalidCredentials;
use Romainnorberg\DataDogApi\Exceptions\NotFound;
use Romainnorberg\DataDogApi\Exceptions\QuotaExceeded;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Request
{
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function request(string $method, string $url, array $options): string
    {
        try {
            $response = $this->httpClient->request($method, $url, $options);

            return $response->getContent();
        } catch (ClientException $clientException) {
            $response = $clientException->getResponse();
            $content = json_decode($response->getContent(false), false, 512, JSON_THROW_ON_ERROR);

            // rate limit
            if (429 === $response->getStatusCode()) {
                throw QuotaExceeded::limitReached(implode(',', $content->errors), $response->getStatusCode(), $response->getInfo('headers'));
            }

            // forbidden
            if (403 === $response->getStatusCode()) {
                throw InvalidCredentials::clientException(implode(',', $content->errors), $response->getStatusCode());
            }

            // not found
            if (404 === $response->getStatusCode()) {
                throw NotFound::error(implode(',', $content->errors), $response->getStatusCode());
            }
        }

        return '{}';
    }
}
