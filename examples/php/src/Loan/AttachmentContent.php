<?php

namespace AggregatesByExample\Loan;

/**
 * Content is not important for application decision processing,
 * we can extract all these information to separate class.
 *
 * Class AttachmentContent
 * @package AggregatesByExample\Loan
 */
class AttachmentContent
{
    public function __construct(public readonly AttachmentId $id)
    {
    }

    // ...
}
