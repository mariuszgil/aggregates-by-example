<?php

namespace AggregatesByExample\Loan\Policy\DecisionProcessing;

use AggregatesByExample\Loan\AttachmentDecision;
use AggregatesByExample\Loan\AttachmentDecisions;
use AggregatesByExample\Loan\Decision;
use AggregatesByExample\Loan\DecisionProcessingPolicy;

readonly class AllDecidedTo implements DecisionProcessingPolicy
{
    public function __construct(private Decision $requiredDecision, private Decision $finalDecision)
    {
    }

    public function process(AttachmentDecisions $decisions): Decision
    {
        /**
         * @var $attachmentDecision AttachmentDecision
         */
        foreach ($decisions as $attachmentDecision) {
            if (!$attachmentDecision->decision->equals($this->requiredDecision)) {
                return Decision::NONE;
            }
        }

        return $this->finalDecision;
    }
}
