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
use VGirol\JsonApiStructure\Exception\CanThrowInvalidArgumentException;
use VGirol\JsonApiStructure\Exception\ValidationException;

class ValidateService
{
    use CanThrowInvalidArgumentException;
    use HaveBitwiseFlag;

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

    public const ROUTE_MAIN = 1;
    public const ROUTE_RELATED = 2;
    public const ROUTE_RELATIONSHIP = 4;
    public const SINGLE_RESOURCE = 8;
    public const RESOURCE_COLLECTION = 16;
    public const TO_ONE_RELATIONSHIP = 32;
    public const TO_MANY_RELATIONSHIP = 64;

    /**
     * Undocumented variable
     *
     * @var VersionService
     */
    protected $version;

    /**
     * The HTTP method of the request
     *
     * @var string
     */
    private $method;

    /**
     * Create a new instance
     *
     * @param string|null $method  The HTTP method of the request
     * @param string|null $version The version of the JSON:API specification
     *
     * @return void
     */
    public function __construct(string $method = null, string $version = null)
    {
        if ($method !== null) {
            $this->setMethod($method);
        }

        $this->version = new VersionService($version);
    }

    /**
     * Set the version of the JSON:API specification
     *
     * @param string $version
     *
     * @return static
     */
    public function setVersion(string $version)
    {
        $this->version->setVersion($version);

        return $this;
    }

    /**
     * Set the HTTP method
     *
     * @param string $method
     *
     * @return static
     */
    public function setMethod(string $method)
    {
        $this->method = \strtoupper($method);

        return $this;
    }

    public function isPost(): bool
    {
        return $this->method === 'POST';
    }

    public function isUpdate(): bool
    {
        return \in_array($this->method, ['PATCH', 'PUT']);
    }

    public function isDelete(): bool
    {
        return $this->method === 'DELETE';
    }

    /**
     * Undocumented function
     *
     * @param int $routeType
     *
     * @return static
     */
    public function setRouteType(int $routeType)
    {
        return $this->selectFlag($routeType, [self::ROUTE_MAIN, self::ROUTE_RELATED, self::ROUTE_RELATIONSHIP]);
    }

    public function isMainRoute(): bool
    {
        return $this->isFlagSet(self::ROUTE_MAIN);
    }

    public function isRelatedRoute(): bool
    {
        return $this->isFlagSet(self::ROUTE_RELATED);
    }

    public function isRelationshipRoute(): bool
    {
        return $this->isFlagSet(self::ROUTE_RELATIONSHIP);
    }

    /**
     * Undocumented function
     *
     * @return static
     */
    public function setCollection()
    {
        return $this->selectFlag(self::RESOURCE_COLLECTION, [self::RESOURCE_COLLECTION, self::SINGLE_RESOURCE]);
    }

    /**
     * Undocumented function
     *
     * @return bool
     */
    public function isCollection(): bool
    {
        return $this->isFlagSet(self::RESOURCE_COLLECTION);
    }

    /**
     * Undocumented function
     *
     * @return static
     */
    public function setSingle()
    {
        return $this->selectFlag(self::SINGLE_RESOURCE, [self::RESOURCE_COLLECTION, self::SINGLE_RESOURCE]);
    }

    /**
     * Undocumented function
     *
     * @return bool
     */
    public function isSingle(): bool
    {
        return $this->isFlagSet(self::SINGLE_RESOURCE);
    }

    /**
     * Undocumented function
     *
     * @param int $relationship
     *
     * @return static
     */
    public function setRelationship(int $relationship)
    {
        $this->setRouteType(ValidateService::ROUTE_RELATIONSHIP);
        $this->selectFlag($relationship, [self::TO_MANY_RELATIONSHIP, self::TO_ONE_RELATIONSHIP]);

        return $this->isFlagSet(self::TO_ONE_RELATIONSHIP) ? $this->setSingle() : $this->setCollection();
    }

    /**
     * Undocumented function
     *
     * @return bool
     */
    public function isToMany(): bool
    {
        return $this->isFlagSet(self::TO_MANY_RELATIONSHIP);
    }

    /**
     * Undocumented function
     *
     * @return bool
     */
    public function isToOne(): bool
    {
        return $this->isFlagSet(self::TO_ONE_RELATIONSHIP);
    }

    public function containsAtLeastOneMember(
        array $expected,
        array $json,
        string $description = '',
        bool $returnResult = false,
        $code = 403
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
        $code = 403
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
        $code = 403
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

    protected function getRule(string $path)
    {
        return $this->version->getRule($path);
    }

    protected function throw(string $message, $code)
    {
        throw new ValidationException($message, $code);
    }

    protected function isValidArgument(int $argument, string $type, $value): void
    {
        switch ($type) {
            case 'array':
                $function = '\\is_array';
                break;
            case 'string':
            default:
                $function = '\\is_string';
                break;
        }

        if (call_user_func($function, $value) == false) {
            static::invalidArgument($argument, $type, $value);
        }
    }

    protected function dataIsRequired(): bool
    {
        return (
            (($this->isMainRoute() || $this->isRelatedRoute())
                && \in_array($this->method, ['POST', 'PATCH', 'PUT']))
            || (($this->isRelationshipRoute())
                && \in_array($this->method, ['POST', 'PATCH', 'PUT', 'DELETE']))
            );
    }

    protected function isAutomatic(): bool
    {
        return (($this->flags === null) || ($this->flags === 0));
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
