<?php

namespace Tests\AggregatesByExample\Availability;

use AggregatesByExample\Availability\Policy\LimitedDuration;
use AggregatesByExample\Availability\Policy\NoGapsBetween;
use AggregatesByExample\Availability\Policy\NoOverlapping;
use AggregatesByExample\Availability\Policy\Rejection;
use AggregatesByExample\Availability\Resource;
use AggregatesByExample\Availability\ResourceId;
use Exception;
use League\Period\Duration;
use League\Period\Period;
use Munus\Collection\GenericList;
use PHPUnit\Framework\TestCase;

class ReservationTest extends TestCase
{
    private Resource $resource;

    private GenericList $policies;

    /**
     * @test
     * @throws Exception
     */
    public function reservationMayBeRequestedRightAfterPreviousOne()
    {
        // Act
        $this->resource->reserve(Period::fromDate('2020-03-01 10:00:00', '2020-03-01 12:00:00'), $this->policies);
        $this->resource->reserve(Period::fromDate('2020-03-01 12:00:00', '2020-03-01 14:00:00'), $this->policies);

        // Assert
        $this->assertEquals(2, $this->resource->getReservedPeriods()->length());
    }

    /**
     * @test
     * @throws Exception
     */
    public function reservationCantOverlapWithPreviousOnes()
    {
        // Arrange
        $this->resource->reserve(Period::fromDate('2020-03-01 10:00:00', '2020-03-01 12:00:00'), $this->policies);

        // Act & Assert
        $this->assertInstanceOf(
            Rejection::class,
            $this->resource->reserve(Period::fromDate('2020-03-01 11:00:00', '2020-03-01 14:00:00'), $this->policies)->getLeft()
        );
    }

    /**
     * @test
     * @throws Exception
     */
    public function reservationCantBeLongerThenGivenLimit()
    {
        // Act & Assert
        $this->assertInstanceOf(
            Rejection::class,
            $this->resource->reserve(Period::fromDate('2020-03-01 10:00:00', '2020-03-01 18:00:00'), $this->policies)->getLeft()
        );
    }

    protected function setUp(): void
    {
        // Arrange
        parent::setUp();

        $this->resource = new Resource(ResourceId::fromString('39acb44d-e4cc-4bb1-869e-f4aaa458751f'));
        $this->policies = GenericList::of(
            new NoOverlapping(),
            new NoGapsBetween(),
            new LimitedDuration(Duration::fromDateString('3 HOURS'))
        );
    }
}
