<?php

declare(strict_types=1);

namespace VGirol\JsonApiStructure\Concern;

use VGirol\JsonApiConstant\Members;
use VGirol\JsonApiStructure\Messages;

/**
 * Assertions relating to the jsonapi object
 */
trait ValidateStructure
{
    /**
     * Asserts that a json document has valid structure.
     *
     * It will do the following checks :
     * 1) checks top-level members (@see assertHasValidTopLevelMembers)
     *
     * Optionaly, if presents, it will checks :
     * 2) primary data (@see assertIsValidPrimaryData)
     * 3) errors object (@see assertIsValidErrorsObject)
     * 4) meta object (@see assertIsValidMetaObject)
     * 5) jsonapi object (@see assertIsValidJsonapiObject)
     * 6) top-level links object (@see assertIsValidTopLevelLinksMember)
     * 7) included object (@see assertIsValidIncludedCollection)
     *
     * @param array   $json
     * @param boolean $strict If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function validateStructure(array $json, bool $strict)
    {
        $this->validateTopLevelMembers($json);

        if (\array_key_exists(Members::DATA, $json)) {
            $this->validatePrimaryData($json[Members::DATA], $strict);

            if (\array_key_exists(Members::INCLUDED, $json)) {
                $this->validateIncludedCollection($json[Members::INCLUDED], $json[Members::DATA], $strict);
            }
        }

        if (\array_key_exists(Members::META, $json)) {
            $this->validateMetaObject($json[Members::META], $strict);
        }

        if (\array_key_exists(Members::ERRORS, $json)) {
            $this->validateErrorsObject($json[Members::ERRORS], $strict);
        }

        if (\array_key_exists(Members::JSONAPI, $json)) {
            $this->validateJsonapiObject($json[Members::JSONAPI], $strict);
        }

        if (\array_key_exists(Members::LINKS, $json)) {
            $this->validateTopLevelLinksMember($json[Members::LINKS], $strict);
        }
    }

    /**
     * Asserts that a json document has valid top-level structure.
     *
     * It will do the following checks :
     * 1) asserts that the json document contains at least one of the following top-level members :
     * "data", "meta" or "errors" (@see assertContainsAtLeastOneMember).
     * 2) asserts that the members "data" and "errors" does not coexist in the same document.
     * 3) asserts that the json document contains only the following members :
     * "data", "errors", "meta", "jsonapi", "links", "included" (@see assertContainsOnlyAllowedMembers).
     * 4) if the json document does not contain a top-level "data" member, the "included" member must not
     * be present either.

     * @param array $json
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function validateTopLevelMembers(array $json)
    {
        $expected = [
            Members::DATA,
            Members::ERRORS,
            Members::META
        ];
        $this->containsAtLeastOneMember(
            $expected,
            $json,
            \sprintf(Messages::TOP_LEVEL_MEMBERS, implode('", "', $expected)),
            false,
            400
        );

        if (array_key_exists(Members::DATA, $json) && array_key_exists(Members::ERRORS, $json)) {
            $this->throw(Messages::TOP_LEVEL_DATA_AND_ERROR, 400);
        }

        $allowed = [
            Members::DATA,
            Members::ERRORS,
            Members::META,
            Members::JSONAPI,
            Members::LINKS,
            Members::INCLUDED
        ];
        $this->containsOnlyAllowedMembers($allowed, $json);

        if (!array_key_exists(Members::DATA, $json) && array_key_exists(Members::INCLUDED, $json)) {
            $this->throw(Messages::TOP_LEVEL_DATA_AND_INCLUDED, 400);
        }
    }

    /**
     * Asserts that a json fragment is a valid top-level links member.
     *
     * It will do the following checks :
     * 1) asserts that the top-level "links" member contains only the following allowed members :
     * "self", "related", "first", "last", "next", "prev" (@see assertIsValidLinksObject).
     *
     * @param array   $json
     * @param boolean $strict If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function validateTopLevelLinksMember($json, bool $strict): void
    {
        $allowed = [
            Members::LINK_SELF,
            Members::LINK_RELATED,
            Members::LINK_PAGINATION_FIRST,
            Members::LINK_PAGINATION_LAST,
            Members::LINK_PAGINATION_NEXT,
            Members::LINK_PAGINATION_PREV
        ];
        $this->validateLinksObject($json, $allowed, $strict);
    }

    /**
     * Asserts a json fragment is a valid primary data object.
     *
     * It will do the following checks :
     * 1) asserts that the primary data is either an object, an array of objects or the `null` value.
     * 2) if the primary data is not null, checks if it is a valid single resource or a valid resource collection
     * (@see assertIsValidResourceObject or @see assertIsValidResourceIdentifierObject).
     *
     * @param array|null $json
     * @param boolean    $strict If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function validatePrimaryData($json, bool $strict): void
    {
        if ($json === null) {
            return;
        }

        if (!is_array($json)) {
            $this->throw(Messages::PRIMARY_DATA_NOT_ARRAY, 400);
        }

        if (\count($json) == 0) {
            return;
        }

        if ($this->isArrayOfObjects($json, '', true)) {
            // Resource collection (Resource Objects or Resource Identifier Objects)
            $this->validatePrimaryCollection($json, true, $strict);

            return;
        }

        // Single Resource (Resource Object or Resource Identifier Object)
        $this->validatePrimarySingle($json, $strict);
    }

    /**
     * Asserts that a collection of included resources is valid.
     *
     * It will do the following checks :
     * 1) asserts that it is an array of objects (@see assertIsArrayOfObjects).
     * 2) asserts that each resource of the collection is valid (@see assertIsValidResourceObject).
     * 3) asserts that each resource in the collection corresponds to an existing resource linkage
     * present in either primary data, primary data relationships or another included resource.
     * 4) asserts that each resource in the collection is unique (i.e. each couple id-type is unique).
     *
     * @param array   $included The included top-level member of the json document.
     * @param array   $data     The primary data of the json document.
     * @param boolean $strict   If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function validateIncludedCollection($included, $data, bool $strict): void
    {
        $this->validateResourceObjectCollection($included, $strict);

        $resIdentifiers = array_merge(
            $this->getAllResourceIdentifierObjects($data),
            $this->getAllResourceIdentifierObjects($included)
        );

        $present = [];
        foreach ($included as $inc) {
            if (!$this->existsInArray($inc, $resIdentifiers)) {
                $this->throw(Messages::INCLUDED_RESOURCE_NOT_LINKED, 400);
            }

            if (!\array_key_exists($inc[Members::TYPE], $present)) {
                $present[$inc[Members::TYPE]] = [];
            }
            if (\in_array($inc[Members::ID], $present[$inc[Members::TYPE]])) {
                $this->throw(Messages::COMPOUND_DOCUMENT_ONLY_ONE_RESOURCE, 400);
            }

            \array_push($present[$inc[Members::TYPE]], $inc[Members::ID]);
        }
    }

    /**
     * Asserts that a collection of resource object is valid.
     *
     * @param array   $list
     * @param boolean $checkType If true, asserts that all resources of the collection are of same type
     * @param boolean $strict    If true, excludes not safe characters when checking members name
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    private function validatePrimaryCollection($list, bool $checkType, bool $strict): void
    {
        $isResourceObject = null;
        foreach ($list as $resource) {
            if ($checkType) {
                // Assert that all resources of the collection are of same type.
                if ($isResourceObject === null) {
                    $isResourceObject = $this->dataIsResourceObject($resource);
                }

                if ($isResourceObject !== $this->dataIsResourceObject($resource)) {
                    $this->throw(Messages::PRIMARY_DATA_SAME_TYPE, 400);
                }
            }

            // Check the resource
            $this->validatePrimarySingle($resource, $strict);
        }
    }

    /**
     * Assert that a single resource object is valid.
     *
     * @param array   $resource
     * @param boolean $strict   If true, excludes not safe characters when checking members name
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    private function validatePrimarySingle($resource, bool $strict): void
    {
        if ($this->dataIsResourceObject($resource)) {
            $this->validateResourceObject($resource, $strict);

            return;
        }

        $this->validateResourceIdentifierObject($resource, $strict);
    }

    /**
     * Checks if a given json fragment is a resource object.
     *
     * @param array $resource
     *
     * @return bool
     */
    private function dataIsResourceObject($resource): bool
    {
        $expected = [
            Members::ATTRIBUTES,
            Members::RELATIONSHIPS,
            Members::LINKS
        ];

        return $this->containsAtLeastOneMember($expected, $resource, '', true);
    }

    /**
     * Get all the resource identifier objects (resource linkage) presents in a collection of resource.
     *
     * @param array $data
     *
     * @return array
     */
    private function getAllResourceIdentifierObjects($data): array
    {
        $arr = [];
        if (\count($data) == 0) {
            return $arr;
        }
        if (!$this->isArrayOfObjects($data, '', true)) {
            $data = [$data];
        }
        foreach ($data as $obj) {
            if (!\array_key_exists(Members::RELATIONSHIPS, $obj)) {
                continue;
            }
            foreach ($obj[Members::RELATIONSHIPS] as $relationship) {
                if (!\array_key_exists(Members::DATA, $relationship)) {
                    continue;
                }
                $arr = \array_merge(
                    $arr,
                    $this->isArrayOfObjects($relationship[Members::DATA], '', true) ?
                        $relationship[Members::DATA] : [$relationship[Members::DATA]]
                );
            }
        }

        return $arr;
    }

    /**
     * Checks if a resource is present in a given array.
     *
     * @param array $needle
     * @param array $arr
     *
     * @return bool
     */
    private function existsInArray($needle, $arr): bool
    {
        foreach ($arr as $resIdentifier) {
            $test = $resIdentifier[Members::TYPE] === $needle[Members::TYPE]
                && $resIdentifier[Members::ID] === $needle[Members::ID];
            if ($test) {
                return true;
            }
        }

        return false;
    }
}
