<?php

declare(strict_types=1);

namespace VGirol\JsonApiStructure;

require_once 'Versions.php';

class VersionService
{
    use CanUseDotPath;

    private $default = VERSION_1_0;

    private $version;

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

    public function getRule(string $path)
    {
        return $this->retrieve($path, $this->cache);
    }

    private function createCache(): void
    {
        $rules = include 'VersionRules.php';

        $major = 1;
        $minor = 0;

        do {
            $version = constant("\VGirol\JsonApiStructure\VERSION_{$major}_{$minor}");
            if (!\array_key_exists($version, $rules)) {
                $minor = 0;
                $major++;
            }

            $this->cache = \array_merge_recursive($this->cache, $rules[$version]);
            $minor++;
        } while ($version != $this->version);
    }
}
