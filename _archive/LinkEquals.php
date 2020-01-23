<?php

declare(strict_types=1);

namespace VGirol\JsonApiStructure\Constraint;

use VGirol\JsonApiConstant\Members;

class LinkEquals extends Constraint
{
    /**
     * @var array|string|null
     */
    private $expected;

    /**
     * Class constructor.
     *
     * @param array|string|null $expected
     */
    public function __construct($expected)
    {
        $this->expected = $expected;
    }

    /**
     * Returns a string representation of the constraint.
     */
    public function toString(): string
    {
        return 'Link is not valid.';
    }

    /**
     * Evaluates the constraint for parameter $other. Returns true if the
     * constraint is met, false otherwise.
     *
     * @param array|string|null $inspected value or object to evaluate
     */
    protected function handle($inspected): bool
    {
        if ($this->expected === null) {
            return ($inspected === null);
        }

        if ($inspected === null) {
            return false;
        }

        /** @var string $href */
        $href = \is_array($inspected) && \array_key_exists(Members::LINK_HREF, $inspected) ?
            $inspected[Members::LINK_HREF] : $inspected;

        /** @var string $expectedHref */
        $expectedHref = \is_array($this->expected) && \array_key_exists(Members::LINK_HREF, $this->expected) ?
            $this->expected[Members::LINK_HREF] : $this->expected;

        $linkElms = explode('?', $href);
        $expectedElms = explode('?', $expectedHref);

        if (count($expectedElms) != count($linkElms)) {
            return false;
        }

        if ($expectedElms[0] != $linkElms[0]) {
            return false;
        }

        if (count($linkElms) == 1) {
            return true;
        }

        $expectedQuery = explode('&', $expectedElms[1]);
        $linkQuery = explode('&', $linkElms[1]);

        if (count($expectedQuery) != count($linkQuery)) {
            return false;
        }

        $diff = array_diff($expectedQuery, $linkQuery);

        return count($diff) === 0;
    }
}
