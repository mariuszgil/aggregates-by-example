<?php

namespace AggregatesByExample\Availability\Policy;

final class Rejection
{
    /**
     * @var string
     */
    private $reason;

    /**
     * Rejection constructor.
     * @param string $reason
     */
    public function __construct(string $reason)
    {
        $this->reason = $reason;
    }

    /**
     * @return string
     */
    public function getReason(): string
    {
        return $this->reason;
    }
}
