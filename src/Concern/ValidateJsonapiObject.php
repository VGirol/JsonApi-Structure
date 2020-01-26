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
     * 1) asserts that the jsonapi object is not an array of objects (@see mustNotBeArrayOfObjects).
     * 2) asserts that the jsonapi object contains only the following allowed members : "version" and "meta"
     * (@see containsOnlyAllowedMembers).
     *
     * Optionaly, if presents, it will :
     * 3) asserts that the version member is a string.
     * 4) asserts that meta member is valid (@see validateMetaObject).
     *
     * @param array   $json
     * @param boolean $strict If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     */
    public function validateJsonapiObject($json, bool $strict): void
    {
        $this->mustNotBeArrayOfObjects($json);

        $this->containsOnlyAllowedMembers(
            $this->getRule('JsonapiObject.Allowed'),
            $json
        );

        if (\array_key_exists(Members::JSONAPI_VERSION, $json)) {
            if (!\is_string($json[Members::JSONAPI_VERSION])) {
                $this->throw(Messages::JSONAPI_OBJECT_VERSION_MEMBER_MUST_BE_STRING, 403);
            }
        }

        if (\array_key_exists(Members::META, $json)) {
            $this->validateMetaObject($json[Members::META], $strict);
        }
    }
}
