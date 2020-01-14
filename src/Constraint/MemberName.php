<?php

declare(strict_types=1);

namespace VGirol\JsonApiStructure\Constraint;

use VGirol\JsonApiStructure\Messages;

class MemberName extends Constraint
{
    /**
     * @var bool
     */
    private $strict;

    /**
     * Class constructor.
     *
     * @param boolean $strict If true, unsafe characters are not allowed when checking members name.
     */
    public function __construct(bool $strict)
    {
        $this->strict = $strict;
    }

    /**
     * Returns a string representation of the constraint.
     */
    public function toString(): string
    {
        return 'Member name is not valid.';
    }

    /**
     * Evaluates the constraint for parameter $json. Returns true if the constraint is met, false otherwise.
     *
     * Asserts that a member name is valid.
     *
     * It will do the following checks :
     * 1) asserts that the name is a string with at least one character.
     * 2) asserts that the name has only allowed characters.
     * 3) asserts that it starts and ends with a globally allowed character.
     *
     * @link https://jsonapi.org/format/#document-member-names-allowed-characters
     *
     * @param mixed $name Value or object to evaluate
     *
     * @return bool
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    protected function handle($name): bool
    {
        if (!\is_string($name)) {
            $this->setFailureMessage(Messages::MEMBER_NAME_IS_NOT_STRING);

            return false;
        }

        if (\strlen($name) < 1) {
            $this->setFailureMessage(Messages::MEMBER_NAME_IS_TOO_SHORT);

            return false;
        }

        // Globally allowed characters
        $globally = '\x{0030}-\x{0039}\x{0041}-\x{005A}\x{0061}-\x{007A}';
        $globallyNotSafe = '\x{0080}-\x{FFFF}';

        // Allowed characters
        $allowed = '\x{002D}\x{005F}';
        $allowedNotSafe = '\x{0020}';

        $regex = "/[^{$globally}{$globallyNotSafe}{$allowed}{$allowedNotSafe}]+/u";
        $safeRegex = "/[^{$globally}{$allowed}]+/u";

        if (\preg_match($this->strict ? $safeRegex : $regex, $name) > 0) {
            $this->setFailureMessage(Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS);

            return false;
        }

        $regex = "/^[{$globally}{$globallyNotSafe}]{1}(?:.*[{$globally}{$globallyNotSafe}]{1})?$/u";
        $safeRegex = "/^[{$globally}]{1}(?:.*[{$globally}]{1})?$/u";
        if (\preg_match($this->strict ? $safeRegex : $regex, $name) == 0) {
            $this->setFailureMessage(Messages::MEMBER_NAME_START_AND_END_WITH_ALLOWED_CHARACTERS);

            return false;
        }

        return true;
    }
}
