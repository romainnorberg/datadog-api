<?php

/*
 * This file is part of the DataDogApi package.
 * (c) Romain Norberg <romainnorberg@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Romainnorberg\DataDogApi\Services\Tests;

use DateTime;
use PHPUnit\Framework\TestCase;
use Romainnorberg\DataDogApi\Http\ClientInterface;
use Romainnorberg\DataDogApi\Services\Metrics\Metrics;

class MetricsTest extends TestCase
{
    /**
     * @test
     */
    public function metrics_list(): void
    {
        $metricList = $this->getMetrics();

        $this->assertCount(427, $metricList->response()->metrics);
    }

    /**
     * @test
     */
    public function has_metric(): void
    {
        $metricList = $this->getMetrics();

        $this->assertTrue($metricList->hasMetric('system.load.1'));

        $this->assertFalse($metricList->hasMetric('System.load.1')); // case sensitive
        $this->assertFalse($metricList->hasMetric('unknown'));
    }

    public function getMetrics(): Metrics
    {
        $requestResponse = file_get_contents(__DIR__.'/../../Fixtures/Http/Response/Metrics/metrics_list.json');
        $service = new Metrics(new MetricsFakeCLient($requestResponse));

        return $service
            ->from(new DateTime())
            ->handle();
    }
}

class MetricsFakeCLient implements ClientInterface
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
