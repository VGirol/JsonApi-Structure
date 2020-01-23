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
     * 1) checks top-level members (@see hasValidTopLevelMembers)
     *
     * Optionaly, if presents, it will checks :
     * 2) primary data (@see validatePrimaryData)
     * 3) errors object (@see validateErrorsObject)
     * 4) meta object (@see validateMetaObject)
     * 5) jsonapi object (@see validateJsonapiObject)
     * 6) top-level links object (@see validateTopLevelLinksMember)
     * 7) included object (@see validateIncludedCollection)
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
            $withPagination = $this->canBePaginated($json);
            $this->validateTopLevelLinksMember($json[Members::LINKS], $withPagination, $strict);
        }
    }

    /**
     * Asserts that a json document has valid top-level structure.
     *
     * It will do the following checks :
     * 1) asserts that the json document contains at least one of the following top-level members :
     * "data", "meta" or "errors" (@see containsAtLeastOneMember).
     * 2) asserts that the members "data" and "errors" does not coexist in the same document.
     * 3) asserts that the json document contains only the following members :
     * "data", "errors", "meta", "jsonapi", "links", "included" (@see containsOnlyAllowedMembers).
     * 4) if the json document does not contain a top-level "data" member, the "included" member must not
     * be present either.

     * @param array $json
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function validateTopLevelMembers(array $json)
    {
        $expected = $this->getRule('Document.AtLeast');
        $this->containsAtLeastOneMember(
            $expected,
            $json,
            \sprintf(Messages::TOP_LEVEL_MEMBERS, implode('", "', $expected)),
            false,
            403
        );

        if (\array_key_exists(Members::DATA, $json) && \array_key_exists(Members::ERRORS, $json)) {
            $this->throw(Messages::TOP_LEVEL_DATA_AND_ERROR, 403);
        }

        $allowed = $this->getRule('Document.Allowed');
        $this->containsOnlyAllowedMembers($allowed, $json);

        if (!\array_key_exists(Members::DATA, $json)) {
            if (\array_key_exists(Members::INCLUDED, $json)) {
                $this->throw(Messages::TOP_LEVEL_DATA_AND_INCLUDED, 403);
            }
            if (!$this->isAutomatic() && $this->dataIsRequired()) {
                $this->throw(Messages::REQUEST_ERROR_NO_DATA_MEMBER, 403);
            }
        }
    }

    /**
     * Asserts a json fragment is a valid primary data object.
     *
     * It will do the following checks :
     * 1) asserts that the primary data is either an object, an array of objects or the `null` value.
     * 2) if the primary data is not null, checks if it is a valid single resource or a valid resource collection
     * (@see validateResourceObject or @see validateResourceIdentifierObject).
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
            if (!$this->isAutomatic() && !($this->isRelationshipRoute() && $this->isToOne())) {
                $this->throw(Messages::REQUEST_ERROR_DATA_MEMBER_NULL, 403);
            }
            return;
        }

        if (!\is_array($json)) {
            $this->throw(sprintf(Messages::REQUEST_ERROR_DATA_MEMBER_NOT_ARRAY, gettype($json)), 403);
        }

        if (\count($json) == 0) {
            if (!$this->isAutomatic() && !($this->isRelationshipRoute() && $this->isToMany())
                || ($this->isRelationshipRoute() && $this->isToMany() && ($this->isPost() || $this->isDelete()))) {
                $this->throw(
                    $this->isCollection() ?  Messages::REQUEST_ERROR_DATA_MEMBER_NOT_COLLECTION :
                        Messages::REQUEST_ERROR_DATA_MEMBER_NOT_SINGLE,
                    403
                );
            }
            return;
        }

        if ($this->isArrayOfObjects($json, true)) {
            if (!$this->isAutomatic() && $this->isSingle()) {
                $this->throw(Messages::REQUEST_ERROR_DATA_MEMBER_NOT_SINGLE, 403);
            }

            // Resource collection (Resource Objects or Resource Identifier Objects)
            $this->validatePrimaryCollection($json, true, $strict);

            return;
        }

        if (!$this->isAutomatic() && $this->isCollection()) {
            $this->throw(Messages::REQUEST_ERROR_DATA_MEMBER_NOT_COLLECTION, 403);
        }

        // Single Resource (Resource Object or Resource Identifier Object)
        $this->validatePrimarySingle($json, $strict);
    }

    /**
     * Asserts that a json fragment is a valid top-level links member.
     *
     * It will do the following checks :
     * 1) asserts that the top-level "links" member contains only the following allowed members :
     * "self", "related" and optionaly pagination links (@see validateLinksObject).
     *
     * @param array   $json
     * @param boolean $withPagination
     * @param boolean $strict         If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function validateTopLevelLinksMember($json, bool $withPagination, bool $strict): void
    {
        $this->canBePaginated($json);
        $allowed = $this->getRule('Document.LinksObject.Allowed');
        if ($withPagination) {
            $allowed = array_merge($allowed, $this->getRule('LinksObject.Pagination'));
        }
        $this->validateLinksObject($json, $allowed, $strict);
    }

    /**
     * Asserts that a collection of included resources is valid.
     *
     * It will do the following checks :
     * 1) asserts that it is an array of objects (@see isArrayOfObjects).
     * 2) asserts that each resource of the collection is valid (@see validateResourceObject).
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

        $resIdentifiers = \array_merge(
            $this->getAllResourceIdentifierObjects($data),
            $this->getAllResourceIdentifierObjects($included)
        );

        $present = [];
        foreach ($included as $inc) {
            if (!$this->existsInArray($inc, $resIdentifiers)) {
                $this->throw(Messages::INCLUDED_RESOURCE_NOT_LINKED, 403);
            }

            if (!\array_key_exists($inc[Members::TYPE], $present)) {
                $present[$inc[Members::TYPE]] = [];
            }
            if (\in_array($inc[Members::ID], $present[$inc[Members::TYPE]])) {
                $this->throw(Messages::COMPOUND_DOCUMENT_ONLY_ONE_RESOURCE, 403);
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
                    $this->throw(Messages::PRIMARY_DATA_SAME_TYPE, 403);
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
        $isResourceObject = $this->isAutomatic() ?
            $this->dataIsResourceObject($resource) :
            !$this->isRelationshipRoute();
        if ($isResourceObject) {
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
        if (!$this->isArrayOfObjects($data, true)) {
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
                    $this->isArrayOfObjects($relationship[Members::DATA], true) ?
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
