<?php

namespace AggregatesByExample\Loan;

class AttachmentDecisions implements \IteratorAggregate, \Countable
{
    /**
     * @var AttachmentDecision[]
     */
    private array $decisions;

    public function __construct(array $decisions)
    {
        $this->decisions = $decisions;
    }

    public static function createFor(array $attachmentsIds): self
    {
        return new self(array_map(function (AttachmentId $attachmentId) {
            return new AttachmentDecision($attachmentId, Decision::NONE, new \DateTimeImmutable());
        }, $attachmentsIds));
    }

    public function append(AttachmentDecision $decision): self
    {
        return new self($this->decisions + [$decision]);
    }

    public function overwrite(AttachmentDecision $decision): self
    {
        if (!$this->containsDecisionFor($decision->attachmentId)) {
            return $this->append($decision);
        }

        $target = [];

        foreach ($this->decisions as $existingDecision) {
            $target[] = $existingDecision->isFor($decision->attachmentId)
                ? $decision
                : $existingDecision;
        }

        return new self($target);
    }

    public function containsDecisionFor(AttachmentId $attachmentId): bool
    {
        foreach ($this->decisions as $existingDecision) {
            if ($existingDecision->isFor($attachmentId)) {
                return true;
            }
        }

        return false;
    }

    public function isDecisionFor(AttachmentId $attachmentId, Decision $decision): bool
    {
        $attachmentDecision = $this->getDecisionFor($attachmentId);

        return !is_null($attachmentDecision) && $attachmentDecision->decision->equals($decision);
    }

    /**
     * Returns latest decision for given attachment.
     *
     * Latest - current implementation relies on position in collection, not on datetime.
     * @todo CHANGE IT
     *
     */
    public function getDecisionFor(AttachmentId $attachmentId): ?AttachmentDecision
    {
        $result = null;

        foreach ($this->decisions as $existingDecision) {
            if ($existingDecision->isFor($attachmentId)) {
                $result = $existingDecision;
            }
        }

        return $result;
    }

    /**
     * @return \ArrayObject|\Traversable
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayObject($this->decisions);
    }

    public function count(): int
    {
        return count($this->decisions);
    }
}
