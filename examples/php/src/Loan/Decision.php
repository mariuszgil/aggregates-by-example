<?php

namespace AggregatesByExample\Loan;

use MyCLabs\Enum\Enum;

class Decision extends Enum
{
    public const NONE = 'none';
    public const ACCEPTED = 'accepted';
    public const REJECTED = 'rejected';
}
