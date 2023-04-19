<?php

namespace AggregatesByExample\Availability\Policy;

use AggregatesByExample\Availability\Policy;
use League\Period\Period;
use Munus\Collection\GenericList;
use Munus\Control\Either;
use Munus\Control\Either\Left;
use Munus\Control\Either\Right;

class NoGapsBetween implements Policy
{
    public function isSatisfied(Period $period, GenericList $reservedPeriods): Either
    {
        $touched = $reservedPeriods->filter(function (Period $reservedPeriod) use ($period) {
            return $reservedPeriod->abuts($period);
        });

        return $reservedPeriods->isEmpty() || $touched->length() >= 1
            ? new Right(new Allowance())
            : new Left(new Rejection('Reservation must abuse with previous ones'));
    }
}
