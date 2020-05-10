<?php

/*
 * This file is part of the DataDogApi package.
 * (c) Romain Norberg <romainnorberg@gmail.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Romainnorberg\DataDogApi\Http\Response\Metrics;

class Serie
{
    /**
     * @var string|null
     */
    public $aggr;
    public string $displayName;
    public int $end;
    public string $expression;
    public int $interval;
    public int $length;
    public string $metric;
    public array $pointlist = [];
    public string $scope;
    public int $start;

    public function withoutPointList()
    {
        $return = clone $this;
        $return->pointlist = [];

        return $return;
    }
}
