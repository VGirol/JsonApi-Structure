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
    public function message()
    {
        $arg = 3;
        $type = 'string';
        $value = null;
        $expected = '/' . \sprintf(
            preg_quote(InvalidArgumentException::MESSAGE),
            $arg,
            preg_quote(' (No Value)'),
            '.*',
            '.*',
            $type
        ) . '/';

        $message = InvalidArgumentHelper::message($arg, $type, $value);

        $this->assertRegExp($expected, $message);
    }

    /**
     * @test
     * @dataProvider messageRegexProvider
     */
    public function messageRegex($value, $substring)
    {
        $arg = 3;
        $type = 'string';
        $expected = '/' . \sprintf(
            preg_quote(InvalidArgumentException::MESSAGE),
            $arg,
            $substring,
            '.*',
            '.*',
            $type
        ) . '/';

        $message = InvalidArgumentHelper::messageRegex($arg, $type, $value);

        $this->assertEquals($expected, $message);
    }

    public function messageRegexProvider()
    {
        return [
            'with value' => [
                666,
                preg_quote(' (' . \gettype(666) . '#' . 666 . ')')
            ],
            'without value' => [
                null,
                '[\s\S]*'
            ]
        ];
    }

    /**
     * @test
     */
    public function factory()
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
