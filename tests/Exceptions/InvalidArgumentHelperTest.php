<?php
namespace VGirol\JsonApiStructure\Tests\Exception;

use VGirol\JsonApiStructure\Exception\InvalidArgumentException;
use VGirol\JsonApiStructure\Exception\InvalidArgumentHelper;
use VGirol\JsonApiStructure\Tests\TestCase;

class InvalidArgumentHelperTest extends TestCase
{
    /**
     * @test
     */
    public function invalidArgumentHelper()
    {
        $arg = 3;
        $type = 'string';
        $value = 666;
        $expected = '/' . \sprintf(
            preg_quote(InvalidArgumentException::MESSAGE),
            $arg,
            preg_quote(' (' . \gettype($value) . '#' . $value . ')'),
            '.*',
            '.*',
            $type
        ) . '/';

        $e = InvalidArgumentHelper::factory($arg, $type, $value);

        $this->assertEquals(1, preg_match($expected, $e->getMessage()));
    }
}
