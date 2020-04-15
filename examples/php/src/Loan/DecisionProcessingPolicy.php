<?php

namespace AggregatesByExample\Loan;

interface DecisionProcessingPolicy
{
    /**
     * @param AttachmentDecisions $decisions
     * @return Decision
     */
    public function process(AttachmentDecisions $decisions): Decision;
}
