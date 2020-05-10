<?php

/*
 * This file is part of the DataDogApi package.
 * (c) Romain Norberg <romainnorberg@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Romainnorberg\DataDogApi\Services;

use Romainnorberg\DataDogApi\Http\ClientInterface;

class Validate implements Service
{
    private ClientInterface $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function __invoke()
    {
        $this->client->request('GET', 'validate', []);

        return true;
    }
}
