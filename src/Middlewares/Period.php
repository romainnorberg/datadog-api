<?php

/*
 * This file is part of the DataDogApi package.
 * (c) Romain Norberg <romainnorberg@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Romainnorberg\DataDogApi\Middlewares;

use Romainnorberg\DataDogApi\Exceptions\InvalidPeriod;

final class Period
{
    public \DateTimeInterface $from;
    public \DateTimeInterface $to;

    public static function create(\DateTimeInterface $from, \DateTimeInterface $to): self
    {
        return new static($from, $to);
    }

    public function __construct(\DateTimeInterface $from, \DateTimeInterface $to)
    {
        if ($from > $to) {
            throw InvalidPeriod::startDateCannotBeAfterEndDate($from, $to);
        }

        $this->from = $from;
        $this->to = $to;
    }
}
