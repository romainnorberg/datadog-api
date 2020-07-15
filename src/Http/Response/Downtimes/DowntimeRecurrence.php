<?php

/*
 * This file is part of the DataDogApi package.
 * (c) Romain Norberg <romainnorberg@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Romainnorberg\DataDogApi\Http\Response\Downtimes;

class DowntimeRecurrence
{
    /**
     * @var int|null
     */
    public $period;

    /**
     * @var string|null
     */
    public $rrule;

    /**
     * @var string|null
     */
    public $type;

    /**
     * @var int|null
     */
    public $until_date;

    /**
     * @var int|null
     */
    public $until_occurrences;

    /**
     * @var array|null
     */
    public $week_days;
}
