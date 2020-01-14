<?php

declare(strict_types=1);

namespace VGirol\JsonApiStructure\Concern;

use VGirol\JsonApiConstant\Members;
use VGirol\JsonApiStructure\Messages;

/**
 * Assertions relating to the links object
 */
trait ValidateLinksObject
{
    /**
     * Asserts that a json fragment is a valid links object.
     *
     * It will do the following checks :
     * 1) asserts that it contains only allowed members (@see assertContainsOnlyAllowedMembers).
     * 2) asserts that each member of the links object is a valid link object (@see assertIsValidLinkObject).
     *
     * @param array         $json
     * @param array<string> $allowedMembers
     * @param boolean       $strict         If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function validateLinksObject($json, array $allowedMembers, bool $strict): void
    {
        if (!\is_array($json)) {
            $this->throw(Messages::LINKS_OBJECT_NOT_ARRAY, 400);
        }

        $this->containsOnlyAllowedMembers(
            $allowedMembers,
            $json
        );

        foreach ($json as $link) {
            $this->validateLinkObject($link, $strict);
        }
    }

    /**
     * Asserts that a json fragment is a valid link object.
     *
     * It will do the following checks :
     * 1) asserts that the link object is a string, an array or the `null` value.
     * 2) in case it is an array :
     *      3) asserts that it has the "href" member.
     *      4) asserts that it contains only the following allowed members : "href" and "meta"
     *       (@see assertContainsOnlyAllowedMembers).
     *      5) if present, asserts that the "meta" object is valid (@see assertIsValidMetaObject).
     *
     * @param array|string|null $json
     * @param boolean           $strict If true, unsafe characters are not allowed when checking members name.
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function validateLinkObject($json, bool $strict): void
    {
        if (($json === null) || \is_string($json)) {
            return;
        }

        if (!\is_array($json)) {
            $this->throw(Messages::LINK_OBJECT_IS_NOT_ARRAY, 400);
        }

        if (!\array_key_exists(Members::LINK_HREF, $json)) {
            $this->throw(Messages::LINK_OBJECT_MISS_HREF_MEMBER, 400);
        }

        $allowed = [
            Members::LINK_HREF,
            Members::META
        ];
        $this->containsOnlyAllowedMembers(
            $allowed,
            $json
        );

        if (\array_key_exists(Members::META, $json)) {
            $this->validateMetaObject($json[Members::META], $strict);
        }
    }
}
