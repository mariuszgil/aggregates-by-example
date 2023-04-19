<?php

namespace AggregatesByExample\Loan\Policy\DecisionRegistration;

use AggregatesByExample\Loan\AttachmentDecision;
use AggregatesByExample\Loan\AttachmentDecisions;
use AggregatesByExample\Loan\DecisionRegistrationPolicy;
use AggregatesByExample\Loan\LoanApplication;
use Exception;

readonly class Deadline implements DecisionRegistrationPolicy
{
    public function __construct(private DecisionRegistrationPolicy $policy, private \DateInterval $interval)
    {
    }

    /**
     * @throws Exception
     */
    public function register(AttachmentDecision $newDecision, LoanApplication $loanApplication): AttachmentDecisions
    {
        if ($loanApplication->getCreated()->add($this->interval) > new \DateTimeImmutable()) {
            throw new \DomainException('Registering new decision after deadline is not allowed');
        }

        return $this->policy->register($newDecision, $loanApplication);
    }
}
