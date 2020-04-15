<?php

namespace AggregatesByExample\Loan;

use Webmozart\Assert\Assert;

final class LoanApplicationId
{
    /**
     * @var string
     */
    private $id;

    /**
     * LoanApplicationId constructor.
     * @param string $id
     */
    private function __construct(string $id)
    {
        Assert::uuid($id);

        $this->id = $id;
    }

    /**
     * @param string $id
     * @return LoanApplicationId
     */
    public static function fromString(string $id): LoanApplicationId
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
