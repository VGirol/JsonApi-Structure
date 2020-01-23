<?php

declare(strict_types=1);

namespace VGirol\JsonApiStructure\Tests\Constraint;

use VGirol\JsonApiStructure\Constraint\LinkEquals;
use VGirol\JsonApiStructure\Exception\ValidationException;
use VGirol\JsonApiStructure\Tests\TestCase;

class LinkEqualsTest extends TestCase
{
    /**
     * @test
     * @dataProvider linkObjectEqualsProvider
     */
    public function linkObjectEquals($expected, $link)
    {
        $constraint = new LinkEquals($expected);

        $this->assertTrue($constraint->evaluate($link, '', true));
    }

    public function linkObjectEqualsProvider()
    {
        return [
            'null' => [
                null,
                null
            ],
            'string' => [
                'url',
                'url'
            ],
            'with query string' => [
                'url?query1=test&query2=anything',
                'url?query1=test&query2=anything'
            ]
        ];
    }

    /**
     * @test
     * @dataProvider linkObjectEqualsFailedProvider
     */
    public function linkObjectEqualsFailed($expected, $link)
    {
        $this->setFailure(ValidationException::class, 'Link is not valid.');

        $constraint = new LinkEquals($expected);

        $this->assertTrue($constraint->evaluate($link, '', false));
    }

    public function linkObjectEqualsFailedProvider()
    {
        return [
            'must be null' => [
                null,
                'not null'
            ],
            'must not be null' => [
                'url',
                null
            ],
            'must have query string' => [
                'url?query=test',
                'url'
            ],
            'must not have query string' => [
                'url',
                'url?query=test'
            ],
            'not same url' => [
                'url1',
                'url2'
            ],
            'not same count of query strings' => [
                'url?query1=test',
                'url?query1=test&query2=anything'
            ],
            'not same query strings' => [
                'url?query1=test',
                'url?query1=anything'
            ]
        ];
    }
}
