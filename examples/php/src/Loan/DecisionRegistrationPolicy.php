<?php

namespace AggregatesByExample\Loan;

interface DecisionRegistrationPolicy
{
    public function register(AttachmentDecision $newDecision, LoanApplication $loanApplication): AttachmentDecisions;
}
