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
     * Assert that a json fragment is a valid meta object.
     *
     * It will do the following checks :
     * 1) validates that the meta object is not an array of objects (@see mustNotBeArrayOfObjects).
     * 2) validates that each member of the meta object is valid (@see validateMemberName).
     *
     * @param array   $json
     * @param boolean $strict If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function validateMetaObject($json, bool $strict): void
    {
        $this->mustNotBeArrayOfObjects($json, Messages::META_OBJECT_MUST_BE_ARRAY, 403);

        foreach (array_keys($json) as $key) {
            $this->validateMemberName($key, $strict);
        }
    }
}
