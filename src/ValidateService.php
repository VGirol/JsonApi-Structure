<?php

declare(strict_types=1);

namespace VGirol\JsonApiStructure;

use VGirol\JsonApiStructure\Concern\ValidateArrays;
use VGirol\JsonApiStructure\Concern\ValidateAttributesObject;
use VGirol\JsonApiStructure\Concern\ValidateErrorsObject;
use VGirol\JsonApiStructure\Concern\ValidateJsonapiObject;
use VGirol\JsonApiStructure\Concern\ValidateLinksObject;
use VGirol\JsonApiStructure\Concern\ValidateMembers;
use VGirol\JsonApiStructure\Concern\ValidateMetaObject;
use VGirol\JsonApiStructure\Concern\ValidateRelationshipsObject;
use VGirol\JsonApiStructure\Concern\ValidateResourceLinkage;
use VGirol\JsonApiStructure\Concern\ValidateResourceObject;
use VGirol\JsonApiStructure\Concern\ValidateStructure;
use VGirol\JsonApiStructure\Constraint\ContainsAtLeastOne;
use VGirol\JsonApiStructure\Constraint\ContainsOnlyAllowedMembers;
use VGirol\JsonApiStructure\Constraint\MemberName;
use VGirol\JsonApiStructure\Exception\ValidationException;

class ValidateService
{
    use ValidateArrays;
    use ValidateAttributesObject;
    use ValidateErrorsObject;
    use ValidateJsonapiObject;
    use ValidateLinksObject;
    use ValidateMembers;
    use ValidateMetaObject;
    use ValidateRelationshipsObject;
    use ValidateResourceLinkage;
    use ValidateResourceObject;
    use ValidateStructure;

    /**
     * Create a new instance
     */
    public function __construct()
    {
        // constructor body
    }

    public function containsAtLeastOneMember(
        array $expected,
        array $json,
        string $description = '',
        bool $returnResult = false,
        $code = 400
    ): bool {
        return $this->constraint(
            ContainsAtLeastOne::class,
            [$expected],
            $json,
            $description,
            $returnResult,
            $code
        );
    }

    public function containsOnlyAllowedMembers(
        array $allowed,
        array $json,
        string $description = '',
        bool $returnResult = false,
        $code = 400
    ): bool {
        return $this->constraint(
            ContainsOnlyAllowedMembers::class,
            [$allowed],
            $json,
            $description,
            $returnResult,
            $code
        );
    }

    public function validateMemberName(
        $name,
        bool $strict,
        string $description = '',
        bool $returnResult = false,
        $code = 400
    ): bool {
        return $this->constraint(
            MemberName::class,
            [$strict],
            $name,
            $description,
            $returnResult,
            $code
        );
    }

    protected function throw(string $message, $code)
    {
        throw new ValidationException($message, $code);
    }

    private function constraint(
        string $class,
        array $consructorArgs,
        $inspected,
        string $description,
        bool $returnResult,
        $code
    ): bool {
        return (new $class(...$consructorArgs))->evaluate($inspected, $description, $returnResult, $code);
    }
}
