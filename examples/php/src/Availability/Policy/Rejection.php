<?php

namespace AggregatesByExample\Availability\Policy;

final readonly class Rejection
{
    public function __construct(public string $reason)
    {
    }
}
