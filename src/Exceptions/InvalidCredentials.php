<?php

/*
 * This file is part of the DataDogApi package.
 * (c) Romain Norberg <romainnorberg@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Romainnorberg\DataDogApi\Exceptions;

use Exception;

class InvalidCredentials extends Exception
{
    public static function credentialsAreMissing()
    {
        return new static('Credentials are missing. You can provide credentials on instantiation or there are loaded from ENV.');
    }
}
