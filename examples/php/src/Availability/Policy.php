<?php

namespace AggregatesByExample\Availability;

use League\Period\Period;
use Munus\Collection\GenericList;
use Munus\Control\Either;

interface Policy
{
    public function isSatisfied(Period $period, GenericList $reservedPeriods): Either;
}
