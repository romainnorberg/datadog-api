<?php

/*
 * This file is part of the DataDogApi package.
 * (c) Romain Norberg <romainnorberg@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Romainnorberg\DataDogApi\Tests;

use PHPUnit\Framework\TestCase;
use Romainnorberg\DataDogApi\DataDogApi;
use Romainnorberg\DataDogApi\Exceptions\InvalidCredentials;
use Romainnorberg\DataDogApi\Services\Downtime;
use Romainnorberg\DataDogApi\Services\Metrics;

class DataDogApiTest extends TestCase
{
    /**
     * @test
     */
    public function constructor_missing_credentials(): void
    {
        $this->expectException(InvalidCredentials::class);

        new DataDogApi();
    }

    /**
     * @test
     */
    public function constructor_should_accept_passing_credentials_from_arguments(): void
    {
        $datadogApi = new DataDogApi('xxx', 'xxx');

        $this->assertInstanceOf(DataDogApi::class, $datadogApi);
    }

    /**
     * @test
     */
    public function constructor_should_accept_missing_credentials_if_exists_in_env(): void
    {
        $_SERVER['DATADOG_API_KEY'] = 'xxx';
        $_SERVER['DATADOG_APPLICATION_KEY'] = 'xxx';

        $datadogApi = new DataDogApi();

        $this->assertInstanceOf(DataDogApi::class, $datadogApi);
    }

    /**
     * @test
     */
    public function validate_should_call_validation_invalid_credentials(): void
    {
        $this->expectException(InvalidCredentials::class);

        $datadogApi = new DataDogApi();
        $datadogApi->validate();
    }

    /**
     * @test
     */
    public function metrics_should_return_service(): void
    {
        $datadogApi = new DataDogApi();

        $this->assertInstanceOf(Metrics::class, $datadogApi->metrics());
    }

    /**
     * @test
     */
    public function downtime_should_return_service(): void
    {
        $datadogApi = new DataDogApi();

        $this->assertInstanceOf(Downtime::class, $datadogApi->downtime());
    }
}
