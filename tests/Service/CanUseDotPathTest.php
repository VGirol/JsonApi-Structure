<?php

namespace VGirol\JsonApiStructure\Tests\Service;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiStructure\CanUseDotPath;
use VGirol\JsonApiStructure\Exception\DotPathException;
use VGirol\JsonApiStructure\Tests\TestCase;

class CanUseDotPathTest extends TestCase
{
    /**
     * @test
     * @dataProvider retrieveProvider
     */
    public function retrieve($path, $expected)
    {
        $array = [
            'first' => 1,
            'second' => [
                'third' => 3,
                'fourth' => 4
            ]
            ];
        $mock = $this->getMockForTrait(CanUseDotPath::class);

        $value = $mock->retrieve($path, $array);

        PHPUnit::assertEquals($expected, $value);
    }

    public function retrieveProvider()
    {
        return [
            'no dot' => [
                'first',
                1
            ],
            'with do' => [
                'second.fourth',
                4
            ]
        ];
    }

    /**
     * @test
     */
    public function retrieveFailed()
    {
        $array = [
            'first' => 1,
            'second' => [
                'third' => 3,
                'fourth' => 4
            ]
        ];
        $path = 'second.error';
        $mock = $this->getMockForTrait(CanUseDotPath::class);

        $this->setFailureException(
            DotPathException::class,
            sprintf(DotPathException::DOT_PATH_ERROR, $path, 'error'),
            0
        );
        $mock->retrieve($path, $array);
    }
}
