<?php

declare(strict_types=1);

namespace VGirol\JsonApiStructure\Concern;

use VGirol\JsonApiStructure\Messages;

/**
 * Validations relating to the meta object
 */
trait ValidateMetaObject
{
    /**
     * Validates that a json fragment is a valid meta object.
     *
     * It will do the following checks :
     * 1) validates that the meta object is not an array of objects (@see isNotArrayOfObjects).
     * 2) validates that each member of the meta object is valid (@see isValidMemberName).
     *
     * @param array   $json
     * @param boolean $strict If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function validateMetaObject($json, bool $strict): void
    {
        $this->isNotArrayOfObjects(
            $json,
            Messages::META_OBJECT_IS_NOT_ARRAY
        );

        foreach (array_keys($json) as $key) {
            $this->validateMemberName($key, $strict);
        }
    }
}
