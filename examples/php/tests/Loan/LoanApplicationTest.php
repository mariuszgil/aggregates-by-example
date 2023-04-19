<?php

namespace Tests\AggregatesByExample\Loan;

use AggregatesByExample\Loan\AttachmentDecisions;
use AggregatesByExample\Loan\AttachmentId;
use AggregatesByExample\Loan\Decision;
use AggregatesByExample\Loan\DecisionRegistrationPolicy;
use AggregatesByExample\Loan\LoanApplication;
use AggregatesByExample\Loan\LoanApplicationId;
use AggregatesByExample\Loan\Policy\DecisionProcessing\AllDecidedTo;
use AggregatesByExample\Loan\Policy\DecisionRegistration\OverwritingDecisions;
use AggregatesByExample\Loan\Policy\DecisionRegistration\SingleDecisions;
use DomainException;
use Exception;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class LoanApplicationTest extends TestCase
{
    /**
     * @test
     * @throws Exception
     */
    public function applicationDecisionIsMadeAfterAllAttachmentDecisions()
    {
        // Arrange
        list($attachmentId1, $attachmentId2) = $this->prepareAttachmentIds(2);
        $loanApplication = $this->prepareLoanApplication([$attachmentId1, $attachmentId2], new SingleDecisions());

        // Act
        $loanApplication->registerDecision($attachmentId1, Decision::ACCEPTED);
        $loanApplication->registerDecision($attachmentId2, Decision::ACCEPTED);

        // Assert
        $this->assertEquals(Decision::ACCEPTED, $loanApplication->getDecision());
    }

    /**
     * @test
     * @throws Exception
     */
    public function applicationDecisionIsNotAvailableBeforeAllAttachmentDecisions()
    {
        // Arrange
        list($attachmentId1, $attachmentId2) = $this->prepareAttachmentIds(2);
        $loanApplication = $this->prepareLoanApplication([$attachmentId1, $attachmentId2], new SingleDecisions());

        // Act
        $loanApplication->registerDecision($attachmentId1, Decision::ACCEPTED);

        // Assert
        $this->assertEquals(Decision::NONE, $loanApplication->getDecision());
    }

    /**
     * @test
     * @throws Exception
     */
    public function changingAttachmentDecisionsAfterApplicationDecisionIsForbidden()
    {
        // Assert
        $this->expectException(DomainException::class);

        // Arrange
        list($attachmentId1, $attachmentId2) = $this->prepareAttachmentIds(2);
        $loanApplication = $this->prepareLoanApplication([$attachmentId1, $attachmentId2], new OverwritingDecisions());

        $loanApplication->registerDecision($attachmentId1, Decision::ACCEPTED);
        $loanApplication->registerDecision($attachmentId2, Decision::ACCEPTED);

        // Act
        $loanApplication->registerDecision($attachmentId2, Decision::REJECTED);
    }

    /**
     * @param int $howMany
     * @return array
     */
    private function prepareAttachmentIds(int $howMany): array
    {
        $ids = [];

        for ($i = 1; $i <= $howMany; $i++) {
            $ids[] = AttachmentId::fromString(Uuid::uuid4()->toString());
        }

        return $ids;
    }

    /**
     * @param array $attachmentIds
     * @param DecisionRegistrationPolicy $registrationPolicy
     * @return LoanApplication
     * @throws Exception
     */
    private function prepareLoanApplication(array $attachmentIds, DecisionRegistrationPolicy $registrationPolicy): LoanApplication
    {
        return new LoanApplication(
            LoanApplicationId::fromString(Uuid::uuid4()->toString()),
            AttachmentDecisions::createFor($attachmentIds),
            $registrationPolicy,
            new AllDecidedTo(Decision::ACCEPTED, Decision::ACCEPTED)
        );
    }
}
