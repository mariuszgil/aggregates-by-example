<?php

namespace AggregatesByExample\Availability;

use League\Period\Period;

class Reservation
{
    /**
     * @var Period
     */
    private $period;

    /**
     * Reservation constructor.
     * @param Period $period
     */
    public function __construct(Period $period)
    {
        $this->period = $period;
    }

    /**
     * @return Period
     */
    public function getPeriod(): Period
    {
        return $this->period;
    }
}
