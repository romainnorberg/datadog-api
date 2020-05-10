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
use Romainnorberg\DataDogApi\Models\Point;
use Romainnorberg\DataDogApi\Services\Timeserie;

class TimeserieTest extends TestCase
{
    /**
     * @test
     */
    public function invalid_period_to_before_from(): void
    {
        $this->expectException(InvalidPeriod::class);

        $service = new Timeserie(new FakeCLient());
        $service
            ->from(new DateTime())
            ->to((new DateTime())->sub(new DateInterval('PT1H')))
            ->query('avg:worker.memory.heapUsed{*}')
            ->handle();
    }

    /**
     * @test
     */
    public function get_max_value_of_point_list(): void
    {
        $service = new Timeserie(new FakeCLient());
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
}

class FakeCLient implements ClientInterface
{
    public function request(string $method, string $url, array $options): array
    {
        return json_decode(file_get_contents(__DIR__.'/../Fixtures/Http/Response/query_simple_time_series.json'), true, 512, JSON_THROW_ON_ERROR);
    }
}
