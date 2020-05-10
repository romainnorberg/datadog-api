<?php

/*
 * This file is part of the DataDogApi package.
 * (c) Romain Norberg <romainnorberg@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Romainnorberg\DataDogApi\Middlewares;

use DateTime;
use Romainnorberg\DataDogApi\Exceptions\InvalidPeriod;

class Period
{
    public DateTime $from;
    public DateTime $to;

    public static function create(DateTime $from, DateTime $to): self
    {
        return new static($from, $to);
    }

    public function __construct(DateTime $from, DateTime $to)
    {
        if ($from > $to) {
            throw InvalidPeriod::startDateCannotBeAfterEndDate($from, $to);
        }

        $this->from = $from;
        $this->to = $to;
    }
}
