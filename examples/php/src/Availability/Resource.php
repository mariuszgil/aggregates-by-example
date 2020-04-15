<?php

namespace AggregatesByExample\Availability;

use League\Period\Period;
use Munus\Collection\GenericList;
use Munus\Control\Either;
use Munus\Control\Either\Left;
use Munus\Control\Either\Right;

class Resource
{
    /**
     * @var ResourceId
     */
    private $resourceId;

    /**
     * @var GenericList<Reservation>
     */
    private $reservations;

    /**
     * Resource constructor.
     * @param ResourceId $resourceId
     */
    public function __construct(ResourceId $resourceId)
    {
        $this->reservations = GenericList::empty();
        $this->resourceId = $resourceId;
    }

    /**
     * @param Period $period
     * @param GenericList<Policy> $policies
     * @return Either
     */
    public function reserve(Period $period, GenericList $policies): Either
    {
        $rejections = $policies
            ->map(function (Policy $policy) use ($period): Either {
                return $policy->isSatisfied($period, $this->getReservedPeriods());
            })
            ->find(function (Either $either): bool {
                return $either->isLeft();
            })
            ->map(function (Either $either) {
                return $either->getLeft();
            });

        if ($rejections->isEmpty()) {
            $reservation = new Reservation($period);
            $this->reservations = $this->reservations->append($reservation);

            return new Right($reservation);
        } else {
            return new Left($rejections->get());
        }
    }

    public function getReservedPeriods(): GenericList
    {
        return $this->reservations->map(function (Reservation $reservation) {
            return $reservation->getPeriod();
        });
    }

    /**
     * @return ResourceId
     */
    public function getResourceId(): ResourceId
    {
        return $this->resourceId;
    }
}
