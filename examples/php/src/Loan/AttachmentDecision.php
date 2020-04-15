<?php

namespace AggregatesByExample\Loan;

class AttachmentDecision
{
    /**
     * @var AttachmentId
     */
    private $attachmentId;

    /**
     * @var Decision
     */
    private $decision;

    /**
     * @var \DateTimeImmutable
     */
    private $created;

    /**
     * AttachmentDecision constructor.
     * @param AttachmentId $attachmentId
     * @param Decision $decision
     * @param \DateTimeImmutable $created
     * @throws \Exception
     */
    public function __construct(AttachmentId $attachmentId, Decision $decision, \DateTimeImmutable $created = null)
    {
        $this->attachmentId = $attachmentId;
        $this->decision = $decision;
        $this->created = $created ?: new \DateTimeImmutable();
    }

    /**
     * @param AttachmentId $attachmentId
     * @return bool
     */
    public function isFor(AttachmentId $attachmentId): bool
    {
        return $this->attachmentId->toString() == $attachmentId->toString();
    }

    /**
     * @return AttachmentId
     */
    public function getAttachmentId(): AttachmentId
    {
        return $this->attachmentId;
    }

    /**
     * @return Decision
     */
    public function getDecision(): Decision
    {
        return $this->decision;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreated(): \DateTimeImmutable
    {
        return $this->created;
    }
}
