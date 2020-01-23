<?php

declare(strict_types=1);

namespace VGirol\JsonApiStructure\Exception;

/**
 * This trait provide a method to throw InvalidArgumentException.
 */
trait CanThrowInvalidArgumentException
{
    /**
     * Throws an InvalidArgumentException because of an invalid argument passed to a method.
     *
     * @param integer $argument
     * @param string  $type
     * @param mixed   $value
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\InvalidArgumentException
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected static function invalidArgument(int $argument, string $type, $value = null): void
    {
        throw InvalidArgumentHelper::factory($argument, $type, $value);
    }
}
