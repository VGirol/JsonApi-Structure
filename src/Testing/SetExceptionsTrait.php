<?php

declare(strict_types=1);

namespace VGirol\JsonApiStructure\Testing;

use PHPUnit\Framework\ExpectationFailedException;

/**
 * Some helpers for testing
 *
 * @codeCoverageIgnore
 */
trait SetExceptionsTrait
{
    /**
     * Set the class name of the expected exception
     *
     * @see \PHPUnit\Framework\TestCase::expectException
     *
     * @param string $exception
     *
     * @return void
     */
    abstract public function expectException(string $exception): void;

    /**
     * Set the message of the expected exception
     *
     * @see \PHPUnit\Framework\TestCase::expectExceptionMessage
     *
     * @param string $message
     *
     * @return void
     */
    abstract public function expectExceptionMessage(string $message): void;

    /**
     * Set the a regular expression for the message of the expected exception
     *
     * @see \PHPUnit\Framework\TestCase::expectExceptionMessageRegExp
     *
     * @param string $messageRegExp
     *
     * @return void
     */
    abstract public function expectExceptionMessageRegExp(string $messageRegExp): void;


    /**
     * Set the expected exception and message when defining a test that will fail.
     *
     * @param string|null $message The failure message could be either a string or a regular expression.
     *
     * @return void
     */
    protected function setFailure(string $className, ?string $message = null, $code = null)
    {
        $fn = (($message !== null) && (strpos($message, '/') === 0))
            ? 'setFailureExceptionRegex' : 'setFailureException';
        $this->{$fn}($className, $message, $code);
    }

    /**
     * Set the expected exception and message when defining a test that will fail.
     *
     * @param string|null $message
     *
     * @return void
     */
    protected function setFailureException(string $className, ?string $message = null, $code = null)
    {
        $this->expectException($className);
        if ($message !== null) {
            $this->expectExceptionMessage($message);
        }
        if ($code !== null) {
            $this->expectExceptionCode($code);
        }
    }

    /**
     * Set the expected exception and message when defining a test that will fail.
     *
     * @param string|null $message
     *
     * @return void
     */
    protected function setFailureExceptionRegex(string $className, ?string $message = null, $code = null)
    {
        $this->expectException($className);
        if ($message !== null) {
            $this->expectExceptionMessageRegExp($message);
        }
        if ($code !== null) {
            $this->expectExceptionCode($code);
        }
    }

    /**
     * Format the failure message as a regular expression.
     *
     * @param string $message
     *
     * @return string
     */
    protected function formatAsRegex(string $message): string
    {
        return '/' . preg_replace(
            "!\%(\+?)('.|[0 ]|)(-?)([1-9][0-9]*|)(\.[1-9][0-9]*|)([%a-zA-Z])!u",
            '.*',
            preg_quote($message)
        ) . '/s';
    }
}
