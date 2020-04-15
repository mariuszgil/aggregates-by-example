<?php

namespace AggregatesByExample\Loan\Policy\DecisionProcessing;

use AggregatesByExample\Loan\AttachmentDecision;
use AggregatesByExample\Loan\AttachmentDecisions;
use AggregatesByExample\Loan\Decision;
use AggregatesByExample\Loan\DecisionProcessingPolicy;

class AllDecidedTo implements DecisionProcessingPolicy
{
    /**
     * @var Decision
     */
    private $requiredDecision;

    /**
     * @var Decision
     */
    private $finalDecision;

    /**
     * AllDecidedTo constructor.
     * @param Decision $requiredDecision
     * @param Decision $finalDecision
     */
    public function __construct(Decision $requiredDecision, Decision $finalDecision)
    {
        $this->requiredDecision = $requiredDecision;
        $this->finalDecision = $finalDecision;
    }

    /**
     * @param AttachmentDecisions $attachmentDecisions
     * @return Decision
     */
    public function process(AttachmentDecisions $attachmentDecisions): Decision
    {
        /**
         * @var $attachmentDecision AttachmentDecision
         */
        foreach ($attachmentDecisions as $attachmentDecision) {
            if (!$attachmentDecision->getDecision()->equals($this->requiredDecision)) {
                return Decision::NONE();
            }
        }

        return $this->finalDecision;
    }
}
