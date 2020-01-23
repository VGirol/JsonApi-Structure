<?php

declare(strict_types=1);

namespace VGirol\JsonApiStructure\Concern;

use VGirol\JsonApiConstant\Members;
use VGirol\JsonApiStructure\Messages;

/**
 * Assertions relating to the resource linkage
 */
trait ValidateResourceLinkage
{
    /**
     * Asserts that a json fragment is a valid resource linkage object.
     *
     * It will do the following checks :
     * 1) asserts that the provided resource linkage is either an object, an array of objects or the `null` value.
     * 2) asserts that the resource linkage or the collection of resource linkage is valid
     * (@see validateResourceIdentifierObject).
     *
     * @param array|null $json
     * @param boolean    $strict If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function validateResourceLinkage($json, bool $strict): void
    {
        if ($json === null) {
            return;
        }

        if (!\is_array($json)) {
            $this->throw(Messages::RESOURCE_LINKAGE_NOT_ARRAY, 403);
        }

        if (\count($json) == 0) {
            return;
        }

        if (!$this->isArrayOfObjects($json, true)) {
            $json = [$json];
        }
        foreach ($json as $resource) {
            $this->validateResourceIdentifierObject($resource, $strict);
        }
    }

    /**
     * Asserts that a json fragment is a valid resource identifier object.
     *
     * It will do the following checks :
     * 1) asserts that the resource has "id" member(@see validateResourceIdMember).
     * 2) asserts that the resource has "type" (@see validateResourceTypeMember) members.
     * 3) asserts that it contains only the following allowed members : "id", "type" and "meta"
     * (@see containsOnlyAllowedMembers).
     *
     * Optionaly, if presents, it will checks :
     * 4) asserts that the meta object is valid (@see validateMetaObject).
     *
     * @param array   $resource
     * @param boolean $strict   If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function validateResourceIdentifierObject($resource, bool $strict): void
    {
        if (!\is_array($resource)) {
            $this->throw(Messages::RESOURCE_IDENTIFIER_IS_NOT_ARRAY, 403);
        }

        $this->validateResourceIdMember($resource);
        $this->validateResourceTypeMember($resource, $strict);

        $this->containsOnlyAllowedMembers($this->getRule('ResourceIdentifierObject.Allowed'), $resource);

        if (\array_key_exists(Members::META, $resource)) {
            $this->validateMetaObject($resource[Members::META], $strict);
        }
    }
}
