<?php

namespace AggregatesByExample\Availability;

use Webmozart\Assert\Assert;

final class ResourceId
{
    private string $id;

    private function __construct(string $id)
    {
        Assert::uuid($id);

        $this->id = $id;
    }

    public static function fromString(string $id): ResourceId
    {
        return new self($id);
    }

    public function toString(): string
    {
        return $this->id;
    }
}
