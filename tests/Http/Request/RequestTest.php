<?php

/*
 * This file is part of the DataDogApi package.
 * (c) Romain Norberg <romainnorberg@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RomainNorberg\DataDogApi\Tests\Http\Request;

use ArgumentCountError;
use PHPUnit\Framework\TestCase;
use RomainNorberg\DataDogApi\Exceptions\QuotaExceeded;
use Romainnorberg\DataDogApi\Http\Client;
use Romainnorberg\DataDogApi\Http\Request\Request;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

class RequestTest extends TestCase
{
    /**
     * @test
     */
    public function constructor_has_required_arguments(): void
    {
        $this->expectException(ArgumentCountError::class);

        new Request();
    }

    /**
     * @test
     */
    public function should_thrown_error_on_rate_limit_reached(): void
    {
        $this->expectException(QuotaExceeded::class);
        $this->expectExceptionMessage(sprintf('Client return an exception (message: `%s` / code: %s / quota reset in ~%d seconds).', 'Rate limit of 600 requests in 3600 seconds reached. Please try again later.', 429, 1298));

        $request = new Request(new RequestLimitFakeHttpClient());

        $request->request('GET', Client::BASE_URL.'query', []);
    }
}

class RequestLimitFakeHttpClient implements HttpClientInterface
{
    private HttpClientInterface $client;

    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        $responses = [
            new MockResponse('{
                                "errors": [
                                    "Rate limit of 600 requests in 3600 seconds reached. Please try again later."
                                ]
                            }', [
                'http_code' => 429,
                'headers'   => [
                    'X-RateLimit-Limit'     => 600,
                    'X-RateLimit-Period'    => 3600,
                    'X-RateLimit-Reset'     => 1298,
                    'X-RateLimit-Remaining' => 0,
                ],
            ]),
        ];

        $httpClient = new MockHttpClient($responses);

        return $httpClient->request($method, $url, $options);
    }

    public function stream($responses, float $timeout = null): ResponseStreamInterface
    {
    }

    public function withOptions(array $options): static
    {
        $clone = clone $this;
        $clone->client = $clone->client->withOptions($options);

        return $clone;
    }
}
