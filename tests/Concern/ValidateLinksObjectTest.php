<?php

declare(strict_types=1);

namespace VGirol\JsonApiStructure\Tests\Concern;

use VGirol\JsonApiConstant\Members;
use VGirol\JsonApiStructure\Exception\ValidationException;
use VGirol\JsonApiStructure\Messages;
use VGirol\JsonApiStructure\Tests\TestCase;
use VGirol\JsonApiStructure\ValidateService;

class ValidateLinksObjectTest extends TestCase
{
    /**
     * @test
     * @dataProvider validLinkObjectProvider
     */
    public function linkObjectIsValid($json, $strict)
    {
        (new ValidateService())->validateLinkObject($json, $strict);
        $this->succeed();
    }

    public function validLinkObjectProvider()
    {
        return [
            'null value' => [
                null,
                false
            ],
            'as string' => [
                'validLink',
                false
            ],
            'as object' => [
                [
                    Members::LINK_HREF => 'validLink',
                    Members::META => [
                        'key' => 'value'
                    ]
                ],
                true
            ]
        ];
    }

    /**
     * @test
     * @dataProvider notValidLinkObjectProvider
     */
    public function linkObjectIsNotValid($json, $strict, $failureMessage)
    {
        $this->setFailure(ValidationException::class, $failureMessage, 400);
        (new ValidateService)->validateLinkObject($json, $strict);
    }

    public function notValidLinkObjectProvider()
    {
        return [
            'not an array' => [
                666,
                false,
                Messages::LINK_OBJECT_IS_NOT_ARRAY
            ],
            'no "href" member' => [
                [
                    Members::META => 'error'
                ],
                false,
                Messages::LINK_OBJECT_MISS_HREF_MEMBER
            ],
            'not only allowed members' => [
                [
                    Members::LINK_HREF => 'valid',
                    Members::META => 'valid',
                    'test' => 'error'
                ],
                false,
                Messages::ONLY_ALLOWED_MEMBERS
            ],
            'meta not valid' => [
                [
                    Members::LINK_HREF => 'valid',
                    Members::META => [
                        'key+' => 'not valid'
                    ]
                ],
                false,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ],
            'meta not safe' => [
                [
                    Members::LINK_HREF => 'valid',
                    Members::META => [
                        'not safe' => 'because of blank character'
                    ]
                ],
                true,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ]
        ];
    }

    /**
     * @test
     */
    public function linksObjectIsValid()
    {
        $json = [
            Members::LINK_SELF => 'url',
            Members::LINK_RELATED => 'url'
        ];
        $allowed = [Members::LINK_SELF, Members::LINK_RELATED];
        $strict = false;

        (new ValidateService())->validateLinksObject($json, $allowed, $strict);
        $this->succeed();
    }

    /**
     * @test
     * @dataProvider notValidLinksObjectProvider
     */
    public function linksObjectIsNotValid($json, $allowed, $strict, $failureMessage)
    {
        $this->setFailure(ValidationException::class, $failureMessage, 400);
        (new ValidateService())->validateLinksObject($json, $allowed, $strict);
    }

    public function notValidLinksObjectProvider()
    {
        return [
            'not an array' => [
                'error',
                [Members::LINK_SELF, Members::LINK_RELATED],
                false,
                Messages::LINKS_OBJECT_NOT_ARRAY
            ],
            'not only allowed members' => [
                [
                    Members::LINK_SELF => 'valid',
                    Members::LINK_PAGINATION_FIRST => 'valid',
                    'test' => 'error'
                ],
                [Members::LINK_SELF, Members::LINK_RELATED],
                false,
                Messages::ONLY_ALLOWED_MEMBERS
            ],
            'link not valid' => [
                [
                    Members::LINK_SELF => 666
                ],
                [Members::LINK_SELF, Members::LINK_RELATED],
                false,
                Messages::LINK_OBJECT_IS_NOT_ARRAY
            ],
            'link has not safe meta member' => [
                [
                    Members::LINK_SELF => [
                        Members::LINK_HREF => 'url',
                        Members::META => [
                            'not safe' => 'because of blank character'
                        ]
                    ]
                ],
                [Members::LINK_SELF, Members::LINK_RELATED],
                true,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ]
        ];
    }
}
