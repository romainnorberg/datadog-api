<?php

/*
 * This file is part of the DataDogApi package.
 * (c) Romain Norberg <romainnorberg@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Romainnorberg\DataDogApi\Exceptions;

use Exception;

final class QuotaExceeded extends Exception
{
    /**
     * @param array<string> $headers
     */
    public static function limitReached(string $message, ?int $code = 0, ?array $headers = []): self
    {
        return new static(sprintf('Client return an exception (message: `%s` / code: %s / quota reset in ~%d seconds).', $message, $code, $headers['X-RateLimit-Reset'] ?? '?'));
    }
}
