<?php

namespace AggregatesByExample\Loan;

class AttachmentDecision
{
    private \DateTimeImmutable $created;

    public function __construct(public readonly AttachmentId $attachmentId, public readonly Decision $decision, \DateTimeImmutable $created = null)
    {
        $this->created = $created ?: new \DateTimeImmutable();
    }

    public function isFor(AttachmentId $attachmentId): bool
    {
        return $this->attachmentId->toString() == $attachmentId->toString();
    }

    public function getCreated(): \DateTimeImmutable
    {
        return $this->created;
    }
}
