<?php

/*
 * This file is part of the DataDogApi package.
 * (c) Romain Norberg <romainnorberg@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Romainnorberg\DataDogApi\Exceptions;

use Romainnorberg\DataDogApi\Http\Client;

class Extras
{
    public static function extraMessage(): string
    {
        return sprintf('Check status page: %s or twitter: %s', Client::STATUS_PAGE, Client::STATUS_TWITTER);
    }
}
