<?php

namespace AggregatesByExample\Loan;

interface DecisionRegistrationPolicy
{
    /**
     * @param AttachmentDecision $newDecision
     * @param LoanApplication $loanApplication
     * @return AttachmentDecisions
     */
    public function register(AttachmentDecision $newDecision, LoanApplication $loanApplication): AttachmentDecisions;
}
