<?php

namespace AggregatesByExample\Loan;

interface DecisionProcessingPolicy
{
    public function process(AttachmentDecisions $decisions): Decision;
}
