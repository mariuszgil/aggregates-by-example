<?php

namespace AggregatesByExample\Loan;

use DateTimeImmutable;
use DomainException;
use Exception;

class LoanApplication
{
    private DateTimeImmutable $created;
    private Decision $decision;


    public function __construct(
        public readonly LoanApplicationId $id,
        private AttachmentDecisions $attachmentDecisions,
        private readonly DecisionRegistrationPolicy $registrationPolicy,
        private readonly DecisionProcessingPolicy $processingPolicy,
        DateTimeImmutable $created = null
    )
    {
        if (count($attachmentDecisions) == 0) {
            throw new DomainException('Loan application must have at least 1 attachment to check');
        }
        $this->decision = Decision::NONE;
        $this->created = $created ?: new DateTimeImmutable();
    }

    /**
     * @throws Exception
     */
    public function registerDecision(AttachmentId $id, Decision $decision): void
    {
        // When application decision is made, no changes are allowed
        if (!$this->decision->equals(Decision::NONE)) {
            throw new DomainException('Registering new decisions is forbidden');
        }

        $this->attachmentDecisions = $this->registrationPolicy->register(
            new AttachmentDecision($id, $decision, new DateTimeImmutable()),
            $this
        );
        $this->decision = $this->processingPolicy->process(
            $this->attachmentDecisions
        );
    }

    public function getCreated(): DateTimeImmutable
    {
        return $this->created;
    }

    public function getDecision(): Decision
    {
        return $this->decision;
    }

    public function getAttachmentDecisions(): AttachmentDecisions
    {
        return $this->attachmentDecisions;
    }
}
