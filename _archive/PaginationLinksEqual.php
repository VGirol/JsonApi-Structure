<?php

declare(strict_types=1);

namespace VGirol\JsonApiStructure\Constraint;

use VGirol\JsonApiConstant\Members;

class PaginationLinksEqual extends Constraint
{
    /**
     * @var array
     */
    private $expected;

    /**
     * The list of the allowed link names for pagination
     *
     * @var array
     */
    private $allowedMembers;

    /**
     * Class constructor.
     *
     * @param array $expected
     */
    public function __construct($expected, $allowedMembers = null)
    {
        $this->expected = $expected;
        $this->allowedMembers = ($allowedMembers === null) ?
            [
                Members::LINK_PAGINATION_FIRST,
                Members::LINK_PAGINATION_LAST,
                Members::LINK_PAGINATION_PREV,
                Members::LINK_PAGINATION_NEXT
            ] :
            $allowedMembers;
    }

    /**
     * Returns a string representation of the constraint.
     */
    public function toString(): string
    {
        return 'Pagination links are not valid.';
    }

    /**
     * Evaluates the constraint for parameter $other. Returns true if the
     * constraint is met, false otherwise.
     *
     * @param array|string|null $inspected value or object to evaluate
     */
    protected function handle($inspected): bool
    {
        // Add missing members with false value
        $cleanExpected = \array_merge(
            \array_fill_keys($this->allowedMembers, false),
            $this->expected
        );
        \asort($cleanExpected);

        // Extract only pagination members from incoming json
        $cleanJson = \array_intersect_key($inspected, \array_flip($this->allowedMembers));
        \asort($cleanJson);

        // Search for unexpected members
        $notExpectedMembers = \array_keys(
            \array_filter(
                $cleanExpected,
                function ($value) {
                    return $value === false;
                }
            )
        );
        if (\count(\array_intersect_key($cleanJson, \array_flip($notExpectedMembers))) !== 0) {
            return false;
        }

        // Extracts expected members
        $expectedMembers = \array_filter(
            $cleanExpected,
            function ($value) {
                return $value !== false;
            }
        );
        if (\array_keys($expectedMembers) != \array_keys($cleanJson)) {
            return false;
        }

        // Extracts members whose value have to be tested
        $expectedValues = \array_filter(
            $expectedMembers,
            function ($value) {
                return $value !== true;
            }
        );

        foreach ($expectedValues as $name => $expectedLink) {
            $constraint = new LinkEquals($expectedLink);
            if ($constraint->evaluate($cleanJson[$name], '', true) === false) {
                return false;
            }
        }

        return true;
    }
}
