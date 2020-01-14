<?php

declare(strict_types=1);

namespace VGirol\JsonApiStructure\Concern;

use VGirol\JsonApiConstant\Members;
use VGirol\JsonApiStructure\Messages;

/**
 * Assertions relating to the errors object
 */
trait ValidateErrorsObject
{
    /**
     * Asserts that a json fragment is a valid errors object.
     *
     * It will do the following checks :
     * 1) asserts that the errors object is an array of objects (@see assertIsArrayOfObjects).
     * 2) asserts that each error object of the collection is valid (@see assertIsValidErrorObject).
     *
     * @param array   $json
     * @param boolean $strict If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function validateErrorsObject($json, bool $strict): void
    {
        $this->isArrayOfObjects(
            $json,
            Messages::ERRORS_OBJECT_NOT_ARRAY
        );

        foreach ($json as $error) {
            $this->validateErrorObject($error, $strict);
        }
    }

    /**
     * Asserts that a json fragment is a valid error object.
     *
     * It will do the following checks :
     * 1) asserts that the error object is not empty.
     * 2) asserts it contains only the following allowed members :
     * "id", "links", "status", "code", "title", "details", "source", "meta" (@see assertContainsOnlyAllowedMembers).
     *
     * Optionaly, if presents, it will checks :
     * 3) asserts that the "status" member is a string.
     * 4) asserts that the "code" member is a string.
     * 5) asserts that the "title" member is a string.
     * 6) asserts that the "details" member is a string.
     * 7) asserts that the "source" member is valid(@see assertIsValidErrorSourceObject).
     * 8) asserts that the "links" member is valid(@see assertIsValidErrorLinksObject).
     * 9) asserts that the "meta" member is valid(@see assertIsValidMetaObject).
     *
     * @param array   $json
     * @param boolean $strict If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function validateErrorObject($json, bool $strict): void
    {
        if (!\is_array($json)) {
            $this->throw(Messages::ERROR_OBJECT_NOT_ARRAY, 400);
        }

        if (\count($json) == 0) {
            $this->throw(Messages::ERROR_OBJECT_NOT_EMPTY, 400);
        }

        $allowed = [
            Members::ID,
            Members::LINKS,
            Members::ERROR_STATUS,
            Members::ERROR_CODE,
            Members::ERROR_TITLE,
            Members::ERROR_DETAILS,
            Members::ERROR_SOURCE,
            Members::META
        ];
        $this->containsOnlyAllowedMembers($allowed, $json);

        $checks = [
            Members::ERROR_STATUS => Messages::ERROR_STATUS_IS_NOT_STRING,
            Members::ERROR_CODE => Messages::ERROR_CODE_IS_NOT_STRING,
            Members::ERROR_TITLE => Messages::ERROR_TITLE_IS_NOT_STRING,
            Members::ERROR_DETAILS => Messages::ERROR_DETAILS_IS_NOT_STRING
        ];

        foreach ($checks as $member => $failureMsg) {
            if (\array_key_exists($member, $json) && !\is_string($json[$member])) {
                $this->throw($failureMsg, 400);
            }
        }

        if (\array_key_exists(Members::ERROR_SOURCE, $json)) {
            $this->validateErrorSourceObject($json[Members::ERROR_SOURCE]);
        }

        if (\array_key_exists(Members::LINKS, $json)) {
            $this->validateErrorLinksObject($json[Members::LINKS], $strict);
        }

        if (\array_key_exists(Members::META, $json)) {
            $this->validateMetaObject($json[Members::META], $strict);
        }
    }

    /**
     * Asserts that a json fragment is a valid error links object.
     *
     * It will do the following checks :
     * 1) asserts that le links object is valid (@see assertIsValidLinksObject with only "about" member allowed).
     *
     * @param array   $json
     * @param boolean $strict If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function validateErrorLinksObject($json, bool $strict): void
    {
        $allowed = [Members::LINK_ABOUT];
        $this->validateLinksObject($json, $allowed, $strict);
    }

    /**
     * Asserts that a json fragment is a valid error source object.
     *
     * It will do the following checks :
     * 1) if the "pointer" member is present, asserts it is a string starting with a "/" character.
     * 2) if the "parameter" member is present, asserts that it is a string.
     *
     * @param array $json
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function validateErrorSourceObject($json): void
    {
        if (!\is_array($json)) {
            $this->throw(Messages::ERROR_SOURCE_OBJECT_NOT_ARRAY, 400);
        }

        if (\array_key_exists(Members::ERROR_POINTER, $json)) {
            if (!\is_string($json[Members::ERROR_POINTER])) {
                $this->throw(Messages::ERROR_SOURCE_POINTER_IS_NOT_STRING, 400);
            }
            if ($json[Members::ERROR_POINTER][0] != '/') {
                $this->throw(Messages::ERROR_SOURCE_POINTER_START, 400);
            }
        }

        if (\array_key_exists(Members::ERROR_PARAMETER, $json)) {
            if (!\is_string($json[Members::ERROR_PARAMETER])) {
                $this->throw(Messages::ERROR_SOURCE_PARAMETER_IS_NOT_STRING, 400);
            }
        }
    }
}
