<?php

namespace AggregatesByExample\Availability\Policy;

use AggregatesByExample\Availability\Policy;
use League\Period\Duration;
use League\Period\Period;
use Munus\Collection\GenericList;
use Munus\Control\Either;
use Munus\Control\Either\Left;
use Munus\Control\Either\Right;

class LimitedDuration implements Policy
{
    private Duration $maxDuration;

    public function __construct(Duration $maxDuration)
    {
        $this->maxDuration = $maxDuration;
    }

    public function isSatisfied(Period $period, GenericList $reservedPeriods): Either
    {
        return $period->durationLessThan($period->withDurationAfterStart($this->maxDuration))
            ? new Right(new Allowance())
            : new Left(new Rejection('Duration limited exceeded'));
    }
}
