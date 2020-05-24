<?php

/*
 * This file is part of the DataDogApi package.
 * (c) Romain Norberg <romainnorberg@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Romainnorberg\DataDogApi\Services\Tests;

use PHPUnit\Framework\TestCase;
use Romainnorberg\DataDogApi\Exceptions\NotFound;
use Romainnorberg\DataDogApi\Exceptions\ServerException;
use Romainnorberg\DataDogApi\Http\Client;
use Romainnorberg\DataDogApi\Http\ClientInterface;
use Romainnorberg\DataDogApi\Http\Request\Request;
use Romainnorberg\DataDogApi\Http\Response\Metrics\MetricMetadataResponse;
use Romainnorberg\DataDogApi\Services\Metrics\MetricMetadata;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class MetricMetadataTest extends TestCase
{
    /**
     * @test
     */
    public function metric_metadata_unknown_metric(): void
    {
        $this->expectException(NotFound::class);
        $this->expectExceptionMessage('Client return an not found error (message: metric_name not found / code 404).');

        $response = new class() implements ClientInterface {
            public function request(string $method, string $url, array $options): string
            {
                $responses = [
                    new MockResponse(file_get_contents(__DIR__.'/../../Fixtures/Http/Response/Metrics/metric_unknown.json'), [
                        'http_code' => 404,
                    ]),
                ];

                $httpClient = new MockHttpClient($responses);
                $request = new Request($httpClient);

                return $request->request($method, Client::BASE_URL.$url, $options);
            }
        };

        $service = new MetricMetadata($response);

        $metricMetadata = $service
            ->metricName('unknown.metric')
            ->handle();

        $this->assertNull($metricMetadata);
    }

    /**
     * @test
     */
    public function metric_metadata_request_timeout(): void
    {
        $this->expectException(ServerException::class);
        $this->expectExceptionMessage('Client server return a Gateway Timeout (message: HTTP 504 returned for "https://api.datadoghq.com/api/v1/metrics/mysql.binlog.disk_use". / code 504).');

        $response = new class() implements ClientInterface {
            public function request(string $method, string $url, array $options): string
            {
                $responses = [
                    new MockResponse('', [
                        'http_code' => 504,
                    ]),
                ];

                $httpClient = new MockHttpClient($responses);
                $request = new Request($httpClient);

                return $request->request($method, Client::BASE_URL.$url, $options);
            }
        };

        $service = new MetricMetadata($response);

        $metricMetadata = $service
            ->metricName('mysql.binlog.disk_use')
            ->handle();

        $this->assertNull($metricMetadata);
    }

    /**
     * @test
     */
    public function metric_metadata_found_case_1(): void
    {
        $requestResponse = file_get_contents(__DIR__.'/../../Fixtures/Http/Response/Metrics/metric_metadata_01.json');
        $service = new MetricMetadata(new MetricMetadataFakeCLient($requestResponse));

        $metricMetadata = $service
            ->metricName('datadog.agent.python.version')
            ->handle()
            ->response();

        $expectedMetricMetadata = (new MetricMetadataResponse());
        $expectedMetricMetadata->short_name = 'py version';
        $expectedMetricMetadata->integration = 'agent_metrics';
        $expectedMetricMetadata->type = 'gauge';

        $this->assertEquals($expectedMetricMetadata, $metricMetadata);
    }

    /**
     * @test
     */
    public function metric_metadata_found_case_2(): void
    {
        $requestResponse = file_get_contents(__DIR__.'/../../Fixtures/Http/Response/Metrics/metric_metadata_02.json');
        $service = new MetricMetadata(new MetricMetadataFakeCLient($requestResponse));

        $metricMetadata = $service
            ->metricName('mysql.binlog.disk_use')
            ->handle()
            ->response();

        $expectedMetricMetadata = (new MetricMetadataResponse());
        $expectedMetricMetadata->type = 'gauge';

        $this->assertEquals($expectedMetricMetadata, $metricMetadata);
    }
}

class MetricMetadataFakeCLient implements ClientInterface
{
    private $requestResponse;

    public function __construct($requestResponse = null)
    {
        $this->requestResponse = $requestResponse;
    }

    public function request(string $method, string $url, array $options): string
    {
        return $this->requestResponse;
    }
}
