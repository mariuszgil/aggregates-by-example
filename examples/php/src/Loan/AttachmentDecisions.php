<?php

namespace AggregatesByExample\Loan;

class AttachmentDecisions implements \IteratorAggregate, \Countable
{
    /**
     * @var AttachmentDecision[]
     */
    private $decisions;

    /**
     * AttachmentDecisions constructor.
     * @param AttachmentDecision[] $decisions
     */
    public function __construct(array $decisions)
    {
        $this->decisions = $decisions;
    }

    /**
     * @param array $attachmentsIds
     * @return static
     */
    public static function createFor(array $attachmentsIds): self
    {
        return new self(array_map(function (AttachmentId $attachmentId) {
            return new AttachmentDecision($attachmentId, Decision::NONE(), new \DateTimeImmutable());
        }, $attachmentsIds));
    }

    /**
     * @param AttachmentDecision $decision
     * @return $this
     */
    public function append(AttachmentDecision $decision): self
    {
        $x = new self($this->decisions + [$decision]);
    }

    /**
     * @param AttachmentDecision $decision
     * @return $this
     */
    public function overwrite(AttachmentDecision $decision): self
    {
        if (!$this->containsDecisionFor($decision->getAttachmentId())) {
            return $this->append($decision);
        }

        $target = [];

        foreach ($this->decisions as $existingDecision) {
            $target[] = $existingDecision->isFor($decision->getAttachmentId())
                ? $decision
                : $existingDecision;
        }

        return new self($target);
    }

    /**
     * @param AttachmentId $attachmentId
     * @return bool
     */
    public function containsDecisionFor(AttachmentId $attachmentId): bool
    {
        foreach ($this->decisions as $existingDecision) {
            if ($existingDecision->isFor($attachmentId)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param AttachmentId $attachmentId
     * @param Decision $decision
     * @return bool
     */
    public function isDecisionFor(AttachmentId $attachmentId, Decision $decision): bool
    {
        $attachmentDecision = $this->getDecisionFor($attachmentId);

        return is_null($attachmentDecision) ? false : $attachmentDecision->getDecision()->equals($decision);
    }

    /**
     * Returns latest decision for given attachment.
     *
     * Latest - current implementation relies on position in collection, not on datetime.
     * @todo CHANGE IT
     *
     * @param AttachmentId $attachmentId
     * @return AttachmentDecision|null
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

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->decisions);
    }
}
