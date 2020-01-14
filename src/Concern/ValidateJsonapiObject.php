<?php

declare(strict_types=1);

namespace VGirol\JsonApiStructure\Concern;

use VGirol\JsonApiConstant\Members;
use VGirol\JsonApiStructure\Messages;

/**
 * Validations relating to the jsonapi object
 */
trait ValidateJsonapiObject
{
    /**
     * Asserts that a json fragment is a valid jsonapi object.
     *
     * It will do the following checks :
     * 1) asserts that the jsonapi object is not an array of objects (@see isNotArrayOfObjects).
     * 2) asserts that the jsonapi object contains only the following allowed members : "version" and "meta"
     * (@see containsOnlyAllowedMembers).
     *
     * Optionaly, if presents, it will checks :
     * 3) asserts that the version member is a string.
     * 4) asserts that meta member is valid (@see isValidMetaObject).
     *
     * @param array   $json
     * @param boolean $strict If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     */
    public function validateJsonapiObject($json, bool $strict): void
    {
        $this->isNotArrayOfObjects(
            $json,
            Messages::OBJECT_NOT_ARRAY
        );

        $allowed = [
            Members::JSONAPI_VERSION,
            Members::META
        ];
        $this->containsOnlyAllowedMembers(
            $allowed,
            $json
        );

        if (\array_key_exists(Members::JSONAPI_VERSION, $json) && !\is_string($json[Members::JSONAPI_VERSION])) {
            $this->throw(Messages::JSONAPI_VERSION_IS_NOT_STRING, 400);
        }

        if (\array_key_exists(Members::META, $json)) {
            $this->validateMetaObject($json[Members::META], $strict);
        }
    }
}
