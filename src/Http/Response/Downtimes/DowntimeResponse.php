<?php

/*
 * This file is part of the DataDogApi package.
 * (c) Romain Norberg <romainnorberg@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Romainnorberg\DataDogApi\Http\Response\Downtimes;

class DowntimeResponse
{
    /**
     * @var DowntimeRecurrence|null
     */
    public $recurrence;

    /**
     * @var bool|null
     */
    public $active;

    /**
     * @var int|null
     */
    public $canceled;

    /**
     * @var int|null
     */
    public $creator_id;

    /**
     * @var bool|null
     */
    public $disabled;

    /**
     * @var int|null
     */
    public $downtime_type;

    /**
     * @var int|null
     */
    public $end;

    /**
     * @var int|null
     */
    public $id;

    /**
     * @var string|null
     */
    public $message;

    /**
     * @var int|null
     */
    public $monitor_id;

    /**
     * @var array|null
     */
    public $monitor_tags;

    /**
     * @var int|null
     */
    public $parent_id;

    /**
     * @var array|null
     */
    public $scope;

    /**
     * @var int|null
     */
    public $start;

    /**
     * @var string|null
     */
    public $timezone;

    /**
     * @var int|null
     */
    public $updater_id;
}
