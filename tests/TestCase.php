<?php

namespace VGirol\JsonApiStructure\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use VGirol\JsonApiStructure\Exception\ValidationException;
use VGirol\PhpunitException\SetExceptionsTrait;

abstract class TestCase extends BaseTestCase
{
    use SetExceptionsTrait;

    /**
     * Assert the success of the current test
     *
     * @param string $message
     *
     * @return void
     */
    public function succeed($message = '')
    {
        $this->assertTrue(true, $message);
    }

    public function setValidationFailure(?string $message = null, $code = null): void
    {
        $this->setFailure(ValidationException::class, $message, $code);
    }
}
