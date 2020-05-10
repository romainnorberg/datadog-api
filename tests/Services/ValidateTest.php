<?php

/*
 * This file is part of the DataDogApi package.
 * (c) Romain Norberg <romainnorberg@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Romainnorberg\DataDogApi\Services\Tests;

use PHPUnit\Framework\TestCase;
use Romainnorberg\DataDogApi\Exceptions\InvalidCredentials;
use Romainnorberg\DataDogApi\Http\Client;
use Romainnorberg\DataDogApi\Http\ClientInterface;
use Romainnorberg\DataDogApi\Http\Request\Request;
use Romainnorberg\DataDogApi\Services\Validate;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class ValidateTest extends TestCase
{
    /**
     * @test
     */
    public function should_thrown_error_when_invalid(): void
    {
        $this->expectException(InvalidCredentials::class);
        $this->expectExceptionMessage(sprintf('Client return an exception (message: %s / code %s)', 'API key required', 403));

        $validate = new Validate(new ValidateFakeCLient());
        $validate();
    }
}

class ValidateFakeCLient implements ClientInterface
{
    public function request(string $method, string $url, array $options): string
    {
        $responses = [
            new MockResponse('{
                                "errors": [
                                    "API key required"
                                ]
                            }', [
                'http_code' => 403,
            ]),
        ];

        $httpClient = new MockHttpClient($responses);

        $request = new Request($httpClient);

        return $request->request('GET', Client::BASE_URL.'validate', []);
    }
}
