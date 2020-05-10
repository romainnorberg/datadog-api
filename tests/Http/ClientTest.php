<?php

/*
 * This file is part of the DataDogApi package.
 * (c) Romain Norberg <romainnorberg@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RomainNorberg\DataDogApi\Tests\Http;

use PHPUnit\Framework\TestCase;
use Romainnorberg\DataDogApi\Http\Client;

class ClientTest extends TestCase
{
    /**
     * @test
     */
    public function constructor_has_required_arguments(): void
    {
        $client = new Client('xxx', 'xxx');

        $this->assertInstanceOf(Client::class, $client);
    }
}
