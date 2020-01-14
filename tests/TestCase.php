<?php

namespace VGirol\JsonApiStructure\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use VGirol\JsonApiStructure\Testing\SetExceptionsTrait;

abstract class TestCase extends BaseTestCase
{
    use SetExceptionsTrait;

    /**
     * assert the success of the current test
     *
     * @param string $message
     *
     * @return void
     */
    public function succeed($message = '')
    {
        $this->assertTrue(true, $message);
    }
}
