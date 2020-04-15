<?php

namespace AggregatesByExample\Availability\Policy;

use AggregatesByExample\Availability\Policy;
use AggregatesByExample\Availability\Reservation;
use League\Period\Period;
use Munus\Collection\GenericList;
use Munus\Control\Either;
use Munus\Control\Either\Left;
use Munus\Control\Either\Right;

class NoOverlapping implements Policy
{
    /**
     * @param Period $period
     * @param GenericList<Period> $periods
     * @return Either
     */
    public function isSatisfied(Period $period, GenericList $reservedPeriods): Either
    {
        $overlapped = $reservedPeriods->filter(function (Period $reservedPeriod) use ($period) {
            return $reservedPeriod->overlaps($period);
        });

        return $overlapped->isEmpty()
            ? new Right(new Allowance())
            : new Left(new Rejection('Reservation cant overlap with previous ones'));
    }
}
