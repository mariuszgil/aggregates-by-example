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
    /**
     * @var AttachmentId
     */
    private $id;

    /**
     * AttachmentContent constructor.
     * @param AttachmentId $id
     */
    public function __construct(AttachmentId $id)
    {
        $this->id = $id;
    }

    // ...
}
