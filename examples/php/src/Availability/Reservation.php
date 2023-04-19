<?php

namespace AggregatesByExample\Availability;

use League\Period\Period;

readonly class Reservation
{
    public function __construct(public Period $period)
    {
    }
}
