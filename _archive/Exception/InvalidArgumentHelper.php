<?php

declare(strict_types=1);

/*
 * This file is inspired from PHPUnit\Util\InvalidArgumentHelper.
 */

namespace VGirol\JsonApiStructure\Exception;

/**
 * Factory for VGirol\JsonApiStructure\Exception\InvalidArgumentException
 *
 * @internal
 */
final class InvalidArgumentHelper
{
    /**
     * Creates a new instance of VGirol\JsonApiAssert\InvalidArgumentException with customized message.
     *
     * @param integer $argument
     * @param string  $type
     * @param mixed   $value
     *
     * @return InvalidArgumentException
     */
    public static function factory(int $argument, string $type, $value = null): InvalidArgumentException
    {
        $stack = \debug_backtrace();

        return new InvalidArgumentException(
            \sprintf(
                InvalidArgumentException::MESSAGE,
                $argument,
                $value !== null ? ' (' . \gettype($value) . '#' . \var_export($value, true) . ')' : ' (No Value)',
                $stack[1]['class'],
                $stack[1]['function'],
                $type
            )
        );
    }
}
