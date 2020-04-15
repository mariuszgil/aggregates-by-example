<?php

namespace AggregatesByExample\Loan\Policy\DecisionRegistration;

use AggregatesByExample\Loan\AttachmentDecision;
use AggregatesByExample\Loan\AttachmentDecisions;
use AggregatesByExample\Loan\DecisionRegistrationPolicy;
use AggregatesByExample\Loan\LoanApplication;

class Deadline implements DecisionRegistrationPolicy
{
    /**
     * @var DecisionRegistrationPolicy
     */
    private $policy;

    /**
     * @var \DateInterval
     */
    private $interval;

    /**
     * Deadline constructor.
     * @param DecisionRegistrationPolicy $policy
     * @param \DateInterval $interval
     */
    public function __construct(DecisionRegistrationPolicy $policy, \DateInterval $interval)
    {
        $this->policy = $policy;
        $this->interval = $interval;
    }

    /**
     * @param AttachmentDecision $newDecision
     * @param LoanApplication $loanApplication
     * @return AttachmentDecisions
     * @throws \Exception
     */
    public function register(AttachmentDecision $newDecision, LoanApplication $loanApplication): AttachmentDecisions
    {
        if ($loanApplication->getCreated()->add($this->interval) > new \DateTimeImmutable()) {
            throw new \DomainException('Registering new decision after deadline is not allowed');
        }

        return $this->policy->register($newDecision, $loanApplication);
    }
}
