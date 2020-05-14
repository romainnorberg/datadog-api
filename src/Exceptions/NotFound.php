<?php

/*
 * This file is part of the DataDogApi package.
 * (c) Romain Norberg <romainnorberg@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Romainnorberg\DataDogApi\Exceptions;

use Exception;

final class NotFound extends Exception
{
    public static function error(string $message, ?int $code = 0): self
    {
        return new static(sprintf('Client return an not found error (message: %s / code %s).', $message, $code));
    }
}
