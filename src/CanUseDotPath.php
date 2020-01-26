<?php

declare(strict_types=1);

namespace VGirol\JsonApiStructure;

use VGirol\JsonApiStructure\Exception\DotPathException;

/**
 * This trait add the ability to retrieve values in arrays using dot notation.
 */
trait CanUseDotPath
{
    /**
     * Retrieve a value in an array using dot notation
     *
     * @param string $path
     * @param array $array
     *
     * @return mixed
     * @throws \VGirol\JsonApiStructure\Exception\DotPathException
     */
    public function retrieve(string $path, array $array)
    {
        $segments = explode('.', $path);
        $value = $array;
        while ($segment = \array_shift($segments)) {
            if (!\array_key_exists($segment, $value)) {
                throw new DotPathException($path, $segment);
            }
            $value = $value[$segment];
        }

        return $value;
    }
}
