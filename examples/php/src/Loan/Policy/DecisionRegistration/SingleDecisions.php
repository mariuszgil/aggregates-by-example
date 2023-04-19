<?php

namespace AggregatesByExample\Loan\Policy\DecisionRegistration;

use AggregatesByExample\Loan\AttachmentDecision;
use AggregatesByExample\Loan\AttachmentDecisions;
use AggregatesByExample\Loan\Decision;
use AggregatesByExample\Loan\DecisionRegistrationPolicy;
use AggregatesByExample\Loan\LoanApplication;

class SingleDecisions implements DecisionRegistrationPolicy
{
    public function register(AttachmentDecision $newDecision, LoanApplication $loanApplication): AttachmentDecisions
    {
        $existingDecisions = $loanApplication->getAttachmentDecisions();

        // 2 cases must be supported here:
        // 1) Collection contains only ACCEPTED or REJECTED decisions, so size is expanding here if needed
        if (!$existingDecisions->containsDecisionFor($newDecision->attachmentId)) {
            return $existingDecisions->append($newDecision);
        }

        // 2) Collection contains all decisions, but some of them are in NONE status. We need to overwrite one of them
        if (!$existingDecisions->isDecisionFor($newDecision->attachmentId, Decision::NONE)) {
            throw new \DomainException('Attachment decision was already provided');
        }

        return $existingDecisions->overwrite($newDecision);
    }
}
