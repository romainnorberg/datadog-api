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
}
