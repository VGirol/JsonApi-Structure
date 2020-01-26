<?php

namespace VGirol\JsonApiStructure\Tests\Concern;

use VGirol\JsonApiConstant\Members;
use VGirol\JsonApiStructure\Messages;
use VGirol\JsonApiStructure\Tests\TestCase;
use VGirol\JsonApiStructure\ValidateService;

class ValidateRelationshipsObjectTest extends TestCase
{
    /**
     * @test
     * @dataProvider validRelationshipLinksObjectProvider
     */
    public function relationshipLinksObjectIsValid($json, $withPagination, $strict)
    {
        (new ValidateService())->validateRelationshipLinksObject($json, $withPagination, $strict);
        $this->succeed();
    }

    public function validRelationshipLinksObjectProvider()
    {
        return [
            'without pagination' => [
                [
                    Members::LINK_SELF => 'url'
                ],
                false,
                false
            ],
            'with pagination' => [
                [
                    Members::LINK_SELF => 'url',
                    Members::LINK_PAGINATION_FIRST => 'url',
                    Members::LINK_PAGINATION_PREV => null,
                    Members::LINK_PAGINATION_NEXT => null,
                    Members::LINK_PAGINATION_LAST => 'url'
                ],
                true,
                false
            ]
        ];
    }

    /**
     * @test
     * @dataProvider notValidRelationshipLinksObjectProvider
     */
    public function relationshipLinksObjectIsNotValid($json, $withPagination, $strict, $failureMessage, $code)
    {
        $this->setFailure($failureMessage, $code);
        (new ValidateService())->validateRelationshipLinksObject($json, $withPagination, $strict);
    }

    public function notValidRelationshipLinksObjectProvider()
    {
        return [
            'not allowed member' => [
                [
                    Members::LINK_SELF => 'url',
                    'anything' => 'not allowed'
                ],
                false,
                false,
                Messages::ONLY_ALLOWED_MEMBERS,
                403
            ],
            'with pagination and not allowed member' => [
                [
                    Members::LINK_SELF => 'url',
                    Members::LINK_PAGINATION_FIRST => 'url',
                    Members::LINK_PAGINATION_LAST => 'url',
                    'anything' => 'not allowed'
                ],
                true,
                false,
                Messages::ONLY_ALLOWED_MEMBERS,
                403
            ]
        ];
    }

    /**
     * @test
     * @dataProvider validRelationshipObjectProvider
     */
    public function relationshipObjectIsValid($json, $strict)
    {
        (new ValidateService())->validateRelationshipObject($json, $strict);
        $this->succeed();
    }

    public function validRelationshipObjectProvider()
    {
        return [
            'empty to one relationship' => [
                [
                    Members::DATA => null
                ],
                false
            ],
            'to one relationship' => [
                [
                    Members::DATA => [
                        Members::TYPE => 'author',
                        Members::ID => '2'
                    ],
                    Members::LINKS => [
                        Members::LINK_SELF => 'http://example.com/articles/1/relationships/author',
                        Members::LINK_RELATED => 'http://example.com/articles/1/author',
                    ],
                    Members::META => [
                        'anything' => 'valid'
                    ]
                ],
                false
            ],
            'empty to many relationship' => [
                [
                    Members::DATA => []
                ],
                false
            ],
            'to many relationship' => [
                [
                    Members::DATA => [
                        [
                            Members::TYPE => 'author',
                            Members::ID => '2'
                        ],
                        [
                            Members::TYPE => 'author',
                            Members::ID => '3'
                        ]
                    ],
                    Members::LINKS => [
                        Members::LINK_SELF => 'http://example.com/articles/1/relationships/author',
                        Members::LINK_RELATED => 'http://example.com/articles/1/author',
                        Members::LINK_PAGINATION_FIRST => 'url',
                        Members::LINK_PAGINATION_NEXT => 'url'
                    ],
                    Members::META => [
                        'anything' => 'valid'
                    ]
                ],
                false
            ]
        ];
    }

    /**
     * @test
     */
    public function validateRelationshipObjectInvalidArgument()
    {
        $json = 'not valid';
        $strict = false;

        $this->setInvalidArgumentException(1, 'array', $json);
        (new ValidateService())->validateRelationshipObject($json, $strict);
    }

    /**
     * @test
     * @dataProvider notValidRelationshipObjectProvider
     */
    public function relationshipObjectIsNotValid($json, $strict, $failureMessage, $code)
    {
        $this->setFailure($failureMessage, $code);
        (new ValidateService())->validateRelationshipObject($json, $strict);
    }

    public function notValidRelationshipObjectProvider()
    {
        return [
            'mandatory member miss' => [
                [
                    'anything' => [
                        'not' => 'valid'
                    ]
                ],
                false,
                sprintf(Messages::CONTAINS_AT_LEAST_ONE, implode(', ', [Members::LINKS, Members::DATA, Members::META])),
                403
            ],
            'meta is not valid' => [
                [
                    Members::DATA => [
                        Members::TYPE => 'test',
                        Members::ID => '2'
                    ],
                    Members::META => [
                        'key+' => 'not valid'
                    ]
                ],
                false,
                Messages::MEMBER_NAME_MUST_NOT_HAVE_RESERVED_CHARACTERS,
                403
            ],
            'links is not valid' => [
                [
                    Members::DATA => [
                        Members::TYPE => 'test',
                        Members::ID => '2'
                    ],
                    Members::LINKS => 'not valid'
                ],
                false,
                Messages::LINKS_OBJECT_NOT_ARRAY,
                403
            ],
            'single resource with pagination' => [
                [
                    Members::DATA => [
                        Members::TYPE => 'test',
                        Members::ID => '2'
                    ],
                    Members::LINKS => [
                        Members::LINK_SELF => 'url',
                        Members::LINK_PAGINATION_FIRST => 'url',
                        Members::LINK_PAGINATION_LAST => 'url'
                    ]
                ],
                false,
                Messages::ONLY_ALLOWED_MEMBERS,
                403
            ],
            'array of resource identifier objects without pagination' => [
                [
                    [
                        Members::DATA => [
                            [
                                Members::TYPE => 'test',
                                Members::ID => '2'
                            ],
                            [
                                Members::TYPE => 'test',
                                Members::ID => '3'
                            ]
                        ],
                        Members::LINKS => [
                            Members::LINK_SELF => 'url',
                            Members::LINK_RELATED => 'url'
                        ]
                    ]
                ],
                false,
                sprintf(Messages::CONTAINS_AT_LEAST_ONE, implode(', ', [Members::LINKS, Members::DATA, Members::META])),
                403
            ]
        ];
    }

    /**
     * @test
     */
    public function relationshipsObjectIsValid()
    {
        $json = [
            'author' => [
                Members::LINKS => [
                    Members::LINK_SELF => 'http://example.com/articles/1/relationships/author',
                    Members::LINK_RELATED => 'http://example.com/articles/1/author'
                ],
                Members::DATA => [
                    Members::TYPE => 'people',
                    Members::ID => '9'
                ]
            ]
        ];
        $strict = false;

        (new ValidateService())->validateRelationshipsObject($json, $strict);
        $this->succeed();
    }

    /**
     * @test
     * @dataProvider notValidRelationshipsObjectProvider
     */
    public function relationshipsObjectIsNotValid($json, $strict, $failureMessage, $code)
    {
        $this->setFailure($failureMessage, $code);
        (new ValidateService())->validateRelationshipsObject($json, $strict);
    }

    public function notValidRelationshipsObjectProvider()
    {
        return [
            'an array of objects' => [
                [
                    ['test' => 'not valid'],
                    ['anything' => 'not valid']
                ],
                false,
                Messages::MUST_NOT_BE_ARRAY_OF_OBJECTS,
                403
            ],
            'no valid member name' => [
                [
                    'author+' => [
                        Members::DATA => [
                            Members::TYPE => 'people',
                            Members::ID => '9'
                        ]
                    ]
                ],
                false,
                Messages::MEMBER_NAME_MUST_NOT_HAVE_RESERVED_CHARACTERS,
                403
            ],
            'no safe member name' => [
                [
                    'author not safe' => [
                        Members::DATA => [
                            Members::TYPE => 'people',
                            Members::ID => '9'
                        ]
                    ]
                ],
                true,
                Messages::MEMBER_NAME_MUST_NOT_HAVE_RESERVED_CHARACTERS,
                403
            ]
        ];
    }
}
