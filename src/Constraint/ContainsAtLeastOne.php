<?php

declare(strict_types=1);

namespace VGirol\JsonApiStructure\Constraint;

use VGirol\JsonApiStructure\Messages;

/**
 * Constraint that checks if a json object contains at least one element among a list of expected elements.
 */
class ContainsAtLeastOne extends Constraint
{
    /**
     * Undocumented variable
     *
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
    public function default(): string
    {
        return sprintf(Messages::CONTAINS_AT_LEAST_ONE, implode(', ', $this->members));
    }

    /**
     * Evaluates the constraint for parameter $inspected. Returns true if the constraint is met, false otherwise.
     *
     * @param mixed  $inspected value or object to evaluate
     *
     * @return boolean
     */
    public function handle($inspected): bool
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
