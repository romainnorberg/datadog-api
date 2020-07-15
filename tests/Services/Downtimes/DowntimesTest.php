<?php

/*
 * This file is part of the DataDogApi package.
 * (c) Romain Norberg <romainnorberg@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Romainnorberg\DataDogApi\Services\Tests;

use PHPUnit\Framework\TestCase;
use Romainnorberg\DataDogApi\Http\ClientInterface;
use Romainnorberg\DataDogApi\Services\Downtime\Downtimes;
use Spatie\Snapshots\MatchesSnapshots;

class DowntimesTest extends TestCase
{
    use MatchesSnapshots;

    /**
     * @test
     */
    public function downtimes_list_all(): void
    {
        $requestResponse = file_get_contents(__DIR__.'/../../Fixtures/Http/Response/Downtimes/downtimes_list_all.json');
        $service = new Downtimes(new DowntimeFakeCLient($requestResponse));

        $downtimes = $service
            ->handle()
            ->response();

        $this->assertMatchesJsonSnapshot($downtimes);
    }

    /**
     * @test
     */
    public function downtimes_list_active(): void
    {
        $requestResponse = file_get_contents(__DIR__.'/../../Fixtures/Http/Response/Downtimes/downtimes_list_active.json');
        $service = new Downtimes(new DowntimeFakeCLient($requestResponse));

        $downtimes = $service
            ->currentOnly()
            ->handle()
            ->response();

        $this->assertMatchesJsonSnapshot($downtimes);
    }

    /**
     * @test
     */
    public function downtimes_list_example(): void
    {
        $requestResponse = file_get_contents(__DIR__.'/../../Fixtures/Http/Response/Downtimes/downtimes_list_example.json');
        $service = new Downtimes(new DowntimeFakeCLient($requestResponse));

        $downtimes = $service
            ->currentOnly()
            ->handle()
            ->response();

        $this->assertMatchesJsonSnapshot($downtimes);
    }
}

class DowntimeFakeCLient implements ClientInterface
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
