<?php

declare(strict_types=1);

namespace VGirol\JsonApiStructure\Constraint;

class ContainsAtLeastOne extends Constraint
{
    /**
     * @var array
     */
    private $members;

    /**
     * Class constructor.
     *
     * @param array $members
     */
    public function __construct(array $members)
    {
        $this->members = $members;
    }

    /**
     * Returns a string representation of the constraint.
     */
    public function toString(): string
    {
        return sprintf('Must contain at least one element of "%s".', implode(', ', $this->members));
    }

    /**
     * Evaluates the constraint for parameter $inspected. Returns true if the constraint is met, false otherwise.
     *
     * @param mixed  $inspected value or object to evaluate
     *
     * @return boolean
     */
    protected function handle($inspected): bool
    {
        if (!\is_array($inspected)) {
            return false;
        }

        foreach ($this->members as $member) {
            if (\array_key_exists($member, $inspected)) {
                return true;
            }
        }

        return false;
    }
}
