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
     * Creates a new instance of VGirol\JsonApiStructure\InvalidArgumentException with customized message.
     *
     * @param integer $argument
     * @param string  $type
     * @param mixed   $value
     *
     * @return InvalidArgumentException
     */
    public static function factory(int $argument, string $type, $value = null): InvalidArgumentException
    {
        return new InvalidArgumentException(static::message($argument, $type, $value));
    }

    /**
     * Format the message for the exception
     *
     * @param integer $argument
     * @param string  $type
     * @param mixed   $value
     *
     * @return string
     */
    public static function message(int $argument, string $type, $value = null): string
    {
        $stack = \debug_backtrace();

        return \sprintf(
            InvalidArgumentException::MESSAGE,
            $argument,
            $value !== null ? ' (' . \gettype($value) . '#' . \var_export($value, true) . ')' : ' (No Value)',
            $stack[4]['class'],
            $stack[4]['function'],
            $type
        );
    }

    /**
     * Get the message of the exception as a regular expression
     *
     * @param integer $argument
     * @param string  $type
     * @param mixed   $value
     *
     * @return string
     */
    public static function messageRegex(int $argument, string $type, $value = null): string
    {
        return \sprintf(
            '/' . \preg_quote(InvalidArgumentException::MESSAGE) . '/',
            $argument,
            ($value !== null) ?
                ' \(' . \gettype($value) . '#' . \preg_quote(\var_export($value, true)) . '\)' : '[\s\S]*',
            '.*',
            '.*',
            \preg_quote($type)
        );
    }
}
