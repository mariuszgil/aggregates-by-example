<?php

namespace AggregatesByExample\Loan\Policy\DecisionRegistration;

use AggregatesByExample\Loan\AttachmentDecision;
use AggregatesByExample\Loan\AttachmentDecisions;
use AggregatesByExample\Loan\DecisionRegistrationPolicy;
use AggregatesByExample\Loan\LoanApplication;

class OverwritingDecisions implements DecisionRegistrationPolicy
{
    /**
     * @param AttachmentDecision $newDecision
     * @param LoanApplication $loanApplication
     * @return AttachmentDecisions
     */
    public function register(AttachmentDecision $newDecision, LoanApplication $loanApplication): AttachmentDecisions
    {
        return $loanApplication->getAttachmentDecisions()->overwrite($newDecision);
    }
}
