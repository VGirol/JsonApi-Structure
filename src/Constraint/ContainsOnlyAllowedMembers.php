<?php

declare(strict_types=1);

namespace VGirol\JsonApiStructure\Constraint;

use VGirol\JsonApiStructure\Messages;

class ContainsOnlyAllowedMembers extends Constraint
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
        return Messages::ONLY_ALLOWED_MEMBERS;
    }

    /**
     * Evaluates the constraint for parameter $json. Returns true if the constraint is met, false otherwise.
     *
     * @param mixed $json Value or object to evaluate
     *
     * @return boolean
     */
    protected function handle($json): bool
    {
        if (!\is_array($json)) {
            return false;
        }

        foreach (\array_keys($json) as $key) {
            if (!\in_array($key, $this->members)) {
                return false;
            }
        }

        return true;
    }
}
