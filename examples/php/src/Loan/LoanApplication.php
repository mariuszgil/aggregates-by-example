<?php

namespace AggregatesByExample\Loan;

class LoanApplication
{
    /**
     * @var LoanApplicationId
     */
    private $id;

    /**
     * @var \DateTimeImmutable
     */
    private $created;

    /**
     * @var Decision
     */
    private $decision;

    /**
     * @var AttachmentDecisions
     */
    private $attachmentDecisions;

    /**
     * @var DecisionRegistrationPolicy
     */
    private $registrationPolicy;

    /**
     * @var DecisionRegistrationPolicy
     */
    private $processingPolicy;

    /**
     * LoanApplication constructor.
     * @param LoanApplicationId $id
     * @param AttachmentDecisions $attachmentDecisions
     * @param DecisionRegistrationPolicy $registrationPolicy
     * @param DecisionProcessingPolicy $processingPolicy
     * @param \DateTimeImmutable $created
     * @throws \Exception
     */
    public function __construct(
        LoanApplicationId $id,
        AttachmentDecisions $attachmentDecisions,
        DecisionRegistrationPolicy $registrationPolicy,
        DecisionProcessingPolicy $processingPolicy,
        \DateTimeImmutable $created = null
    )
    {
        if (count($attachmentDecisions) == 0) {
            throw new \DomainException('Loan application must have at least 1 attachment to check');
        }

        $this->id = $id;
        $this->attachmentDecisions = $attachmentDecisions;
        $this->registrationPolicy = $registrationPolicy;
        $this->processingPolicy = $processingPolicy;
        $this->decision = Decision::NONE();
        $this->created = $created ?: new \DateTimeImmutable();
    }

    /**
     * @param AttachmentId $id
     * @param Decision $decision
     * @throws \Exception
     */
    public function registerDecision(AttachmentId $id, Decision $decision): void
    {
        // When application decision is made, no changes are allowed
        if (!$this->decision->equals(Decision::NONE())) {
            throw new \DomainException('Registering new decisions is forbidden');
        }

        $this->attachmentDecisions = $this->registrationPolicy->register(
            new AttachmentDecision($id, $decision, new \DateTimeImmutable()),
            $this
        );
        $this->decision = $this->processingPolicy->process(
            $this->attachmentDecisions
        );
    }

    /**
     * @return LoanApplicationId
     */
    public function getId(): LoanApplicationId
    {
        return $this->id;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreated(): \DateTimeImmutable
    {
        return $this->created;
    }

    /**
     * @return Decision
     */
    public function getDecision(): Decision
    {
        return $this->decision;
    }

    /**
     * @return AttachmentDecisions
     */
    public function getAttachmentDecisions(): AttachmentDecisions
    {
        return $this->attachmentDecisions;
    }
}
