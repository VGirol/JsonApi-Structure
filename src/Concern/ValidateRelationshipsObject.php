<?php

declare(strict_types=1);

namespace VGirol\JsonApiStructure\Concern;

use VGirol\JsonApiConstant\Members;

/**
 * Assertions relating to the relationships object
 */
trait ValidateRelationshipsObject
{
    /**
     * Asserts that a json fragment is a valid relationships object.
     *
     * It will do the following checks :
     * 1) asserts that the relationships object is not an array of objects (@see assertIsNotArrayOfObjects).
     * 2) asserts that each relationship of the collection has a valid name (@see assertIsValidMemberName)
     * and is a valid relationship object (@see assertIsValidRelationshipObject).
     *
     * @param array   $json
     * @param boolean $strict If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function validateRelationshipsObject($json, bool $strict): void
    {
        $this->isNotArrayOfObjects($json);

        foreach ($json as $key => $relationship) {
            $this->validateMemberName($key, $strict);
            $this->validateRelationshipObject($relationship, $strict);
        }
    }

    /**
     * Asserts that a json fragment is a valid relationship object.
     *
     * It will do the following checks :
     * 1) asserts that the relationship object contains at least one of the following member : "links", "data", "meta"
     * (@see assertContainsAtLeastOneMember).
     *
     * Optionaly, if presents, it will checks :
     * 2) asserts that the data member is valid (@see assertIsValidResourceLinkage).
     * 3) asserts that the links member is valid (@see assertIsValidRelationshipLinksObject).
     * 4) asserts that the meta object is valid (@see assertIsValidMetaObject).
     *
     * @param array   $json
     * @param boolean $strict If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function validateRelationshipObject(array $json, bool $strict): void
    {
        $this->containsAtLeastOneMember(
            [
                Members::LINKS,
                Members::DATA,
                Members::META
            ],
            $json
        );

        if (\array_key_exists(Members::DATA, $json)) {
            $data = $json[Members::DATA];
            $this->validateResourceLinkage($data, $strict);
        }

        if (\array_key_exists(Members::LINKS, $json)) {
            $links = $json[Members::LINKS];
            $withPagination = \array_key_exists(Members::DATA, $json)
                && $this->isArrayOfObjects($json[Members::DATA], '', true);
            $this->validateRelationshipLinksObject($links, $withPagination, $strict);
        }

        if (\array_key_exists(Members::META, $json)) {
            $this->validateMetaObject($json[Members::META], $strict);
        }
    }

    /**
     * Asserts that a json fragment is a valid link object extracted from a relationship object.
     *
     * It will do the following checks :
     * 1) asserts that the links object is valid (@see assertIsValidLinksObject)
     * with the following allowed members : "self", "related"
     * and eventually pagination links ("first", "last", "prev" and "next").
     *
     * @param array   $json
     * @param boolean $withPagination
     * @param boolean $strict         If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function validateRelationshipLinksObject($json, bool $withPagination, bool $strict): void
    {
        $allowed = [
            Members::LINK_SELF,
            Members::LINK_RELATED
        ];
        if ($withPagination) {
            $allowed = array_merge(
                $allowed,
                [
                    Members::LINK_PAGINATION_FIRST,
                    Members::LINK_PAGINATION_LAST,
                    Members::LINK_PAGINATION_NEXT,
                    Members::LINK_PAGINATION_PREV
                ]
            );
        }
        $this->validateLinksObject($json, $allowed, $strict);
    }
}
