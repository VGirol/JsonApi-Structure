<?php

declare(strict_types=1);

namespace VGirol\JsonApiStructure\Concern;

use VGirol\JsonApiStructure\Messages;

/**
 * Assertions relating to the attributes object
 */
trait ValidateAttributesObject
{
    /**
     * Asserts that a json fragment is a valid attributes object.
     *
     * It will do the following checks :
     * 1) asserts that attributes object is not an array of objects (@see mustNotBeArrayOfObjects).
     * 2) asserts that attributes object has no member with forbidden name (@see fieldHasNoForbiddenMemberName).
     * 3) asserts that each member name of the attributes object is valid (@see validateMemberName).
     *
     * @param array   $json
     * @param boolean $strict If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function validateAttributesObject($json, bool $strict): void
    {
        $this->mustNotBeArrayOfObjects($json, Messages::ATTRIBUTES_OBJECT_MUST_BE_ARRAY, 403);

        $this->fieldHasNoForbiddenMemberName($json);

        foreach (\array_keys($json) as $key) {
            $this->validateMemberName($key, $strict);
        }
    }

    /**
     * Asserts that a field object has no forbidden member name.
     *
     * Asserts that a field object (i.e., a resource object’s attributes or one of its relationships)
     * has no forbidden member name.
     *
     * It will do the following checks :
     * 1) asserts that each member name of the field is not a forbidden name (@see isNotForbiddenMemberName).
     * 2) if the field has nested objects, it will checks each all.
     *
     * @param mixed $field
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function fieldHasNoForbiddenMemberName($field): void
    {
        if (!\is_array($field)) {
            return;
        }

        foreach ($field as $key => $value) {
            // For objects, $key is a string
            // For arrays of objects, $key is an integer
            if (\is_string($key)) {
                $this->isNotForbiddenMemberName($key);
            }
            $this->fieldHasNoForbiddenMemberName($value);
        }
    }

    /**
     * Asserts that a member name is not forbidden (like "relationships" or "links").
     *
     * @param string $name
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function isNotForbiddenMemberName($name): void
    {
        $this->isValidArgument(1, 'string', $name);

        if (\in_array($name, $this->getRule('MemberName.Forbidden'))) {
            $this->throw(Messages::MEMBER_NAME_NOT_ALLOWED, 403);
        }
    }
}
