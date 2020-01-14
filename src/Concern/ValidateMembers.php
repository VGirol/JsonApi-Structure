<?php

declare(strict_types=1);

namespace VGirol\JsonApiStructure\Concern;

use VGirol\JsonApiConstant\Members;
use VGirol\JsonApiStructure\Messages;

/**
 * Assertions relating to the object's members
 */
trait ValidateMembers
{
    /**
     * Asserts that a json object has an expected member.
     *
     * @param string $expected
     * @param array  $json
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function hasMember(string $expected, array $json): void
    {
        if (!\array_key_exists($expected, $json)) {
            $this->throw(sprintf(Messages::HAS_MEMBER, $expected), 400);
        }
    }

    /**
     * Asserts that a json object has expected members.
     *
     * @param array<string> $expected
     * @param array         $json
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function hasMembers(array $expected, array $json): void
    {
        foreach ($expected as $key) {
            $this->hasMember($key, $json);
        }
    }

    /**
     * Asserts that a json object has only expected members.
     *
     * @param array<string> $expected
     * @param array         $json
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function hasOnlyMembers(array $expected, array $json): void
    {
        if (\array_keys($json) != $expected) {
            $this->throw(sprintf(Messages::HAS_ONLY_MEMBERS, implode(', ', $expected)), 400);
        }
    }

    /**
     * Asserts that a json object not has an unexpected member.
     *
     * @param string $expected
     * @param array  $json
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function notHasMember(string $expected, array $json): void
    {
        if (\array_key_exists($expected, $json)) {
            $this->throw(sprintf(Messages::NOT_HAS_MEMBER, $expected), 400);
        }
    }

    /**
     * Asserts that a json object not has unexpected members.
     *
     * @param array<string> $expected
     * @param array         $json
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function notHasMembers(array $expected, array $json): void
    {
        foreach ($expected as $key) {
            $this->notHasMember($key, $json);
        }
    }

    /**
     * Asserts that a json object has a "data" member.
     *
     * @see hasMember
     *
     * @param array $json
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function hasData($json): void
    {
        $this->hasMember(Members::DATA, $json);
    }

    /**
     * Asserts that a json object has an "attributes" member.
     *
     * @see hasMember
     *
     * @param array $json
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function hasAttributes($json): void
    {
        $this->hasMember(Members::ATTRIBUTES, $json);
    }

    /**
     * Asserts that a json object has a "links" member.
     *
     * @see hasMember
     *
     * @param array $json
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function hasLinks($json): void
    {
        $this->hasMember(Members::LINKS, $json);
    }

    /**
     * Asserts that a json object has a "meta" member.
     *
     * @see hasMember
     *
     * @param array $json
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function hasMeta($json): void
    {
        $this->hasMember(Members::META, $json);
    }

    /**
     * Asserts that a json object has an "included" member.
     *
     * @see hasMember
     *
     * @param array $json
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function hasIncluded($json): void
    {
        $this->hasMember(Members::INCLUDED, $json);
    }

    /**
     * Asserts that a json object has a "relationships" member.
     *
     * @see hasMember
     *
     * @param array $json
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function hasRelationships($json): void
    {
        $this->hasMember(Members::RELATIONSHIPS, $json);
    }

    /**
     * Asserts that a json object has an "errors" member.
     *
     * @see hasMember
     *
     * @param array $json
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function hasErrors($json): void
    {
        $this->hasMember(Members::ERRORS, $json);
    }

    /**
     * Asserts that a json object has a "jsonapi" member.
     *
     * @see hasMember
     *
     * @param array $json
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function hasJsonapi($json): void
    {
        $this->hasMember(Members::JSONAPI, $json);
    }
}
