<?php

/*
 * This file is part of the DataDogApi package.
 * (c) Romain Norberg <romainnorberg@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Romainnorberg\DataDogApi\Request;

use PHPUnit\Framework\TestCase;
use Romainnorberg\DataDogApi\Http\Request\Request;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

class RequestTest extends TestCase
{
    /**
     * @test
     */
    public function should_return_empty_body_on_unknown_exception(): void
    {
        $httpClient = new class() implements HttpClientInterface {
            public function request(string $method, string $url, ?array $options = null): ResponseInterface
            {
                return new MockResponse('', [
                    'http_code' => 408,
                ]);
            }

            public function stream($responses, float $timeout = null): ResponseStreamInterface
            {
            }

            public function withOptions(array $options): static
            {
                // TODO: Implement withOptions() method.
            }
        };

        $request = new Request($httpClient);

        $this->assertSame('', $request->request('GET', 'http://httpbin.org'));
    }
}
