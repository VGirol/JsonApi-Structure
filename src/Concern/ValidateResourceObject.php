<?php

declare(strict_types=1);

namespace VGirol\JsonApiStructure\Concern;

use VGirol\JsonApiConstant\Members;
use VGirol\JsonApiStructure\Messages;

/**
 * Assertions relating to the resource object
 */
trait ValidateResourceObject
{
    /**
     * Asserts that a json fragment is a valid collection of resource objects.
     *
     * It will do the following checks :
     * 1) asserts that the provided resource collection is either an empty array or an array of objects
     * (@see isArrayOfObjects).
     * 2) asserts that the collection of resources is valid (@see validateResourceObject).
     *
     * @param array|null $json
     * @param boolean    $strict If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function validateResourceObjectCollection($json, bool $strict): void
    {
        if ($json === null) {
            return;
        }

        if (!\is_array($json)) {
            $this->throw(Messages::RESOURCE_COLLECTION_NOT_ARRAY, 403);
        }

        if (\count($json) == 0) {
            return;
        }

        $this->isArrayOfObjects($json);

        foreach ($json as $resource) {
            $this->validateResourceObject($resource, $strict);
        }
    }

    /**
     * Asserts that a json fragment is a valid resource.
     *
     * It will do the following checks :
     * 1) asserts that the resource object has valid top-level structure
     * (@see validateResourceObjectTopLevelStructure).
     * 2) asserts that the resource object has valid "type" and "id" members
     * (@see validateResourceIdMember and @see validateResourceTypeMember).
     * 3) asserts that the resource object has valid fields (@see hasValidFields).
     *
     * Optionaly, if presents, it will checks :
     * 4) asserts thats the resource object has valid "attributes" member.
     * 5) asserts thats the resource object has valid "relationships" member.
     * 6) asserts thats the resource object has valid "links" member.
     * 7) asserts thats the resource object has valid "meta" member.
     *
     * @param array   $json
     * @param boolean $strict If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function validateResourceObject($json, bool $strict): void
    {
        $this->validateResourceObjectTopLevelStructure($json, $strict);

        if (\array_key_exists(Members::ATTRIBUTES, $json)) {
            $this->validateAttributesObject($json[Members::ATTRIBUTES], $strict);
        }

        if (\array_key_exists(Members::RELATIONSHIPS, $json)) {
            $this->validateRelationshipsObject($json[Members::RELATIONSHIPS], $strict);
        }

        if (\array_key_exists(Members::LINKS, $json)) {
            $this->validateResourceLinksObject($json[Members::LINKS], $strict);
        }

        if (\array_key_exists(Members::META, $json)) {
            $this->validateMetaObject($json[Members::META], $strict);
        }

        $this->validateFields($json);
    }

    /**
     * Asserts that a resource object has a valid top-level structure.
     *
     * It will do the following checks :
     * 1) asserts that the resource has an "id" member.
     * 2) asserts that the resource has a "type" member.
     * 3) asserts that the resource contains at least one of the following members :
     * "attributes", "relationships", "links", "meta" (@see containsAtLeastOneMember).
     * 4) asserts that the resource contains only the following allowed members :
     * "id", "type", "meta", "attributes", "links", "relationships" (@see containsOnlyAllowedMembers).
     *
     * @param array   $resource
     * @param boolean $strict   If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function validateResourceObjectTopLevelStructure($resource, $strict): void
    {
        if (!\is_array($resource)) {
            $this->throw(Messages::RESOURCE_IS_NOT_ARRAY, 403);
        }

        $this->validateResourceIdMember($resource);
        $this->validateResourceTypeMember($resource, $strict);

        $this->containsAtLeastOneMember($this->getRule('ResourceObject.AtLeast'), $resource);

        $this->containsOnlyAllowedMembers($this->getRule('ResourceObject.Allowed'), $resource);
    }

    /**
     * Asserts that a resource id member is valid.
     *
     * It will do the following checks :
     * 1) asserts that the "id" member is not empty.
     * 2) asserts that the "id" member is a string.
     *
     * @param array $resource
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function validateResourceIdMember($resource): void
    {
        if (!\array_key_exists(Members::ID, $resource)) {
            if ($this->isPost()) {
                return;
            }

            $this->throw(Messages::RESOURCE_ID_MEMBER_IS_ABSENT, 403);
        }

        if (!\is_string($resource[Members::ID])) {
            $this->throw(Messages::RESOURCE_ID_MEMBER_IS_NOT_STRING, 403);
        }

        if ($resource[Members::ID] == '') {
            $this->throw(Messages::RESOURCE_ID_MEMBER_IS_EMPTY, 403);
        }
    }

    /**
     * Asserts that a resource type member is valid.
     *
     * It will do the following checks :
     * 1) asserts that the "type" member is not empty.
     * 2) asserts that the "type" member is a string.
     * 3) asserts that the "type" member has a valid value (@see validateMemberName).
     *
     * @param array   $resource
     * @param boolean $strict   If true, excludes not safe characters when checking members name
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function validateResourceTypeMember($resource, bool $strict): void
    {
        if (!\array_key_exists(Members::TYPE, $resource)) {
            $this->throw(Messages::RESOURCE_TYPE_MEMBER_IS_ABSENT, 403);
        }

        if (!\is_string($resource[Members::TYPE])) {
            $this->throw(Messages::RESOURCE_TYPE_MEMBER_IS_NOT_STRING, 403);
        }

        if ($resource[Members::TYPE] == '') {
            $this->throw(Messages::RESOURCE_TYPE_MEMBER_IS_EMPTY, 403);
        }

        $this->validateMemberName($resource[Members::TYPE], $strict);
    }

    /**
     * Asserts that a json fragment is a valid resource links object.
     *
     * It will do the following checks :
     * 1) asserts that le links object is valid (@see validateLinksObject) with only "self" member allowed.
     *
     * @param array   $json
     * @param boolean $strict If true, excludes not safe characters when checking members name
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function validateResourceLinksObject($json, bool $strict): void
    {
        $this->validateLinksObject($json, $this->getRule('ResourceObject.LinksObject.Allowed'), $strict);
    }

    /**
     * Asserts that a resource object has valid fields (i.e., resource object’s attributes and its relationships).
     *
     * It will do the following checks :
     * 1) asserts that each attributes member and each relationship name is valid
     * (@see isNotForbiddenResourceFieldName)
     *
     * @param array $resource
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function validateFields($resource): void
    {
        if (\array_key_exists(Members::ATTRIBUTES, $resource)) {
            foreach (\array_keys($resource[Members::ATTRIBUTES]) as $name) {
                $this->isNotForbiddenResourceFieldName((string) $name);
            }
        }

        if (array_key_exists(Members::RELATIONSHIPS, $resource)) {
            foreach (\array_keys($resource[Members::RELATIONSHIPS]) as $name) {
                $this->isNotForbiddenResourceFieldName((string) $name);

                if (\array_key_exists(Members::ATTRIBUTES, $resource)
                    && \array_key_exists($name, $resource[Members::ATTRIBUTES])
                ) {
                    $this->throw(Messages::FIELDS_HAVE_SAME_NAME, 403);
                }
            }
        }
    }

    /**
     * Asserts that a resource field name is not a forbidden name (like "type" or "id").
     *
     * @param string $name
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function isNotForbiddenResourceFieldName(string $name): void
    {
        if (\in_array($name, $this->getRule('ResourceObject.FieldName.Forbidden'))) {
            $this->throw(Messages::FIELDS_NAME_NOT_ALLOWED, 403);
        }
    }
}
