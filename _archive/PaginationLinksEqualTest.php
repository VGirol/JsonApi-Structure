<?php

declare(strict_types=1);

namespace VGirol\JsonApiStructure\Tests\Asserts\Content;

use VGirol\JsonApiConstant\Members;
use VGirol\JsonApiStructure\Constraint\PaginationLinksEqual;
use VGirol\JsonApiStructure\Exception\ValidationException;
use VGirol\JsonApiStructure\Tests\TestCase;

class PaginationLinksEqualTest extends TestCase
{
    /**
     * @test
     */
    public function paginationLinksEqual()
    {
        $expected = [
            Members::LINK_PAGINATION_FIRST => 'urlFirst',
            Members::LINK_PAGINATION_NEXT => true,
            Members::LINK_PAGINATION_LAST => false
        ];
        $json = [
            Members::LINK_SELF => 'url',
            Members::LINK_PAGINATION_FIRST => 'urlFirst',
            Members::LINK_PAGINATION_NEXT => 'urlNext'
        ];

        $constraint = new PaginationLinksEqual($expected);

        $this->assertTrue($constraint->evaluate($json, '', true));
    }

    /**
     * @test
     * @dataProvider paginationLinksEqualsFailedProvider
     */
    public function paginationLinksEqualsFailed($expected, $failureMsg)
    {
        $json = [
            Members::LINK_SELF => 'url',
            Members::LINK_PAGINATION_FIRST => 'urlFirst',
            Members::LINK_PAGINATION_NEXT => 'urlNext',
            Members::LINK_PAGINATION_LAST => 'urlLast'
        ];

        $this->setFailure(ValidationException::class, $failureMsg);

        $constraint = new PaginationLinksEqual($expected);

        $constraint->evaluate($json, '', false);
    }

    public function paginationLinksEqualsFailedProvider()
    {
        return [
            'has too many members' => [
                [
                    Members::LINK_PAGINATION_FIRST => 'urlFirst',
                    Members::LINK_PAGINATION_NEXT => false,
                    Members::LINK_PAGINATION_PREV => false,
                    Members::LINK_PAGINATION_LAST => 'urlLast'
                ],
                'Pagination links are not valid.'
            ],
            'has not all expected member' => [
                [
                    Members::LINK_PAGINATION_FIRST => 'urlFirst',
                    Members::LINK_PAGINATION_NEXT => 'urlNext',
                    Members::LINK_PAGINATION_PREV => 'urlPrev',
                    Members::LINK_PAGINATION_LAST => 'urlLast'
                ],
                'Pagination links are not valid.'
            ],
            'not same value' => [
                [
                    Members::LINK_PAGINATION_FIRST => 'urlFirstError',
                    Members::LINK_PAGINATION_NEXT => 'urlNext',
                    Members::LINK_PAGINATION_PREV => false,
                    Members::LINK_PAGINATION_LAST => 'urlLast'
                ],
                'Pagination links are not valid.'
            ]
        ];
    }
}
