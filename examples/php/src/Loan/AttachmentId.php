<?php

namespace AggregatesByExample\Loan;

use Webmozart\Assert\Assert;

final class AttachmentId
{
    private string $id;

    private function __construct(string $id)
    {
        Assert::uuid($id);

        $this->id = $id;
    }

    public static function fromString(string $id): AttachmentId
    {
        return new self($id);
    }

    public function toString(): string
    {
        return $this->id;
    }
}
