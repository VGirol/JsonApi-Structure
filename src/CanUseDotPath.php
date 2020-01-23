<?php

declare(strict_types=1);

namespace VGirol\JsonApiStructure;

use VGirol\JsonApiStructure\Exception\DotPathException;

trait CanUseDotPath
{
    protected function retrieve($path, $array)
    {
        $segments = explode('.', $path);
        $value = $array;
        while ($segment = \array_shift($segments)) {
            if (!\array_key_exists($segment, $value)) {
                throw new DotPathException(
                    sprintf('Path "%s" is not valid : segment "%s" does not exists.', $path, $segment)
                );
            }
            $value = $value[$segment];
        }

        return $value;
    }
}
