<?php

namespace AggregatesByExample\Loan;

use MyCLabs\Enum\Enum;

enum Decision: string
{
    case NONE = 'none';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';

    public function equals(Decision $other): bool
    {
        return $this->value === $other->value;
    }
}
