<?php

namespace AggregatesByExample\Loan;

use Webmozart\Assert\Assert;

final class AttachmentId
{
    /**
     * @var string
     */
    private $id;

    /**
     * AttachmentId constructor.
     * @param string $id
     */
    private function __construct(string $id)
    {
        Assert::uuid($id);

        $this->id = $id;
    }

    /**
     * @param string $id
     * @return AttachmentId
     */
    public static function fromString(string $id): AttachmentId
    {
        return new self($id);
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->id;
    }
}
