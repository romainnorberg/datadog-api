<?php

/*
 * This file is part of the DataDogApi package.
 * (c) Romain Norberg <romainnorberg@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Romainnorberg\DataDogApi\Exceptions;

use Exception;

final class ServerException extends Exception
{
    public static function gatewayTimeout(string $message, ?int $code = 0): self
    {
        return new static(sprintf('Client server return a Gateway Timeout (message: %s / code %s). %s', $message, $code, Extras::extraMessage()));
    }
}
