<?php

declare(strict_types=1);

namespace VGirol\JsonApiStructure;

use VGirol\JsonApiConstant\Versions;

/**
 * Service to manage the version of the specification that is used when validating documents.
 */
class VersionService
{
    use CanUseDotPath;

    /**
     * Undocumented variable
     *
     * @var string
     */
    private $default = Versions::VERSION_1_0;

    /**
     * Undocumented variable
     *
     * @var string
     */
    private $version;

    /**
     * Undocumented variable
     *
     * @var array
     */
    private $cache = [];

    /**
     * Create a new instance
     *
     * @param string|null $version The version of the JSON:API specification
     *
     * @return void
     */
    public function __construct(string $version = null)
    {
        $this->setVersion($version ?? $this->default);
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
        $this->version = $version;
        $this->createCache();

        return $this;
    }

    /**
     * Get the version of the JSON:API specification
     *
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * Undocumented function
     *
     * @param string $path
     *
     * @return mixed
     * @throws \VGirol\JsonApiStructure\Exception\DotPathException
     */
    public function getRule(string $path)
    {
        return $this->retrieve($path, $this->cache);
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    protected function getRulesFileContent(): array
    {
        return include __DIR__ . '/VersionRules.php';
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    private function createCache(): void
    {
        $rules = $this->getRulesFileContent();

        $major = 1;
        $minor = 0;

        do {
            $version = constant("\\VGirol\\JsonApiConstant\\Versions::VERSION_{$major}_{$minor}");
            if (!\array_key_exists($version, $rules)) {
                $minor = 0;
                $major++;
            }

            $this->cache = \array_merge_recursive($this->cache, $rules[$version]);
            $minor++;
        } while ($version != $this->version);
    }
}
