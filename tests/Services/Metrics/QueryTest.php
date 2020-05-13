<?php

/*
 * This file is part of the DataDogApi package.
 * (c) Romain Norberg <romainnorberg@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Romainnorberg\DataDogApi\Services\Tests;

use DateInterval;
use DateTime;
use PHPUnit\Framework\TestCase;
use Romainnorberg\DataDogApi\Exceptions\InvalidPeriod;
use Romainnorberg\DataDogApi\Http\ClientInterface;
use Romainnorberg\DataDogApi\Http\Response\Metrics\Point;
use Romainnorberg\DataDogApi\Services\Metrics\Query;

class QueryTest extends TestCase
{
    /**
     * @test
     */
    public function invalid_period_to_before_from(): void
    {
        $this->expectException(InvalidPeriod::class);

        $service = new Query(new TimeSerieFakeCLient());
        $service
            ->from(new DateTime())
            ->to((new DateTime())->sub(new DateInterval('PT1H')))
            ->query('avg:worker.memory.heapUsed{*}')
            ->handle();
    }

    /**
     * @test
     */
    public function get_max_value_of_point_list_with_one_serie(): void
    {
        $requestResponse = file_get_contents(__DIR__.'/../../Fixtures/Http/Response/Metrics/query_simple_time_series.json');
        $service = new Query(new TimeSerieFakeCLient($requestResponse));
        $metric = $service
            ->from(new DateTime())
            ->to(new DateTime())
            ->query('avg:worker.memory.heapUsed{*}')
            ->handle();

        $expectedPoint = new Point();
        $expectedPoint->timestamp = 1588943700000.0;
        $expectedPoint->value = 411617808.0;

        $this->assertEquals($expectedPoint, $metric->maxPoint());
    }

    /**
     * @test
     */
    public function get_max_value_of_point_list_with_multiple_serie(): void
    {
        $requestResponse = file_get_contents(__DIR__.'/../../Fixtures/Http/Response/Metrics/query_multiple_time_series.json');
        $service = new Query(new TimeSerieFakeCLient($requestResponse));
        $metric = $service
            ->from(new DateTime())
            ->to(new DateTime())
            ->query('system.cpu.idle{*}by{host}')
            ->handle();

        $expectedPoint = new Point();
        $expectedPoint->timestamp = 1589111160000.0;
        $expectedPoint->value = 99.82061767578125;

        $this->assertEquals($expectedPoint, $metric->maxPoint());
    }

    /**
     * @test
     */
    public function get_max_value_of_point_list_with_empty_response(): void
    {
        $requestResponse = file_get_contents(__DIR__.'/../../Fixtures/Http/Response/Metrics/query_empty.json');
        $service = new Query(new TimeSerieFakeCLient($requestResponse));
        $metric = $service
            ->from(new DateTime())
            ->to(new DateTime())
            ->query('system.cpu.idle{*}by{host}')
            ->handle();

        $this->assertNull($metric->maxPoint());
    }
}

class TimeSerieFakeCLient implements ClientInterface
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
