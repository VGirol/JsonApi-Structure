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
     * (@see assertIsValidResourceIdentifierObject).
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
            $this->throw(Messages::RESOURCE_LINKAGE_NOT_ARRAY, 400);
        }

        if (\count($json) == 0) {
            return;
        }

        if (!$this->isArrayOfObjects($json, '', true)) {
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
     * 1) asserts that the resource as "id" (@see assertResourceIdMember)
     * and "type" (@see assertResourceTypeMember) members.
     * 2) asserts that it contains only the following allowed members : "id", "type" and "meta"
     * (@see assertContainsOnlyAllowedMembers).
     *
     * Optionaly, if presents, it will checks :
     * 3) asserts that the meta object is valid (@see assertIsValidMetaObject).
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
            $this->throw(Messages::RESOURCE_IDENTIFIER_IS_NOT_ARRAY, 400);
        }

        $this->validateResourceIdMember($resource);

        $this->validateResourceTypeMember($resource, $strict);

        $allowed = [
            Members::ID,
            Members::TYPE,
            Members::META
        ];
        $this->containsOnlyAllowedMembers($allowed, $resource);

        if (\array_key_exists(Members::META, $resource)) {
            $this->validateMetaObject($resource[Members::META], $strict);
        }
    }
}
