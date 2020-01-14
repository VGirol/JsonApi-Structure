<?php

declare(strict_types=1);

namespace VGirol\JsonApiStructure\Tests\Concern;

use VGirol\JsonApiConstant\Members;
use VGirol\JsonApiStructure\Exception\ValidationException;
use VGirol\JsonApiStructure\Messages;
use VGirol\JsonApiStructure\Tests\TestCase;
use VGirol\JsonApiStructure\ValidateService;

class ValidateStructureTest extends TestCase
{
    /**
     * @test
     * @dataProvider validStructureProvider
     */
    public function documentHasValidStructure($json, $strict)
    {
        (new ValidateService())->validateStructure($json, $strict);
        $this->succeed();
    }

    public function validStructureProvider()
    {
        return [
            'with data' => [
                [
                    Members::LINKS => [
                        Members::LINK_SELF => 'http://example.com/articles',
                        Members::LINK_PAGINATION_FIRST => 'url',
                        Members::LINK_PAGINATION_LAST => 'url'
                    ],
                    Members::DATA => [
                        [
                            Members::TYPE => 'articles',
                            Members::ID => '1',
                            Members::ATTRIBUTES => [
                                Members::ERROR_TITLE => 'JSON:API paints my bikeshed!'
                            ]
                        ],
                        [
                            Members::TYPE => 'articles',
                            Members::ID => '2',
                            Members::ATTRIBUTES => [
                                Members::ERROR_TITLE => 'Rails is Omakase'
                            ],
                            Members::RELATIONSHIPS => [
                                'test' => [
                                    Members::DATA => [
                                        Members::TYPE => 'relation',
                                        Members::ID => '12'
                                    ]
                                ]
                            ]
                        ]
                    ],
                    Members::META => [
                        'anything' => 'valid'
                    ],
                    Members::INCLUDED => [
                        [
                            Members::TYPE => 'relation',
                            Members::ID => '12',
                            Members::ATTRIBUTES => [
                                'anything' => 'valid'
                            ]
                        ]
                    ]
                ],
                false
            ],
            'with errors' => [
                [
                    Members::ERRORS => [
                        [
                            Members::ERROR_CODE => 'E13'
                        ],
                        [
                            Members::ERROR_CODE => 'E14'
                        ]
                    ],
                    Members::JSONAPI => [
                        Members::JSONAPI_VERSION => 'valid'
                    ]
                ],
                false
            ]
        ];
    }

    /**
     * @test
     * @dataProvider notValidStructureProvider
     */
    public function documentHasNotValidStructure($json, $strict, $failureMessage)
    {
        $this->setFailure(ValidationException::class, $failureMessage, 400);
        (new ValidateService())->validateStructure($json, $strict);
    }

    public function notValidStructureProvider()
    {
        return [
            'no valid top-level member' => [
                [
                    Members::LINKS => [
                        Members::LINK_SELF => 'http://example.com/articles',
                    ],
                    Members::DATA => [
                        Members::TYPE => 'articles',
                        Members::ID => '1',
                        Members::ATTRIBUTES => [
                            Members::ERROR_TITLE => 'First'
                        ]
                    ],
                    'anything' => 'not valid'
                ],
                false,
                Messages::ONLY_ALLOWED_MEMBERS
            ],
            'no valid primary data' => [
                [
                    Members::LINKS => [
                        Members::LINK_SELF => 'http://example.com/articles',
                    ],
                    Members::DATA => [
                        Members::TYPE => 'articles',
                        Members::ID => 1,
                        Members::ATTRIBUTES => [
                            Members::ERROR_TITLE => 'First'
                        ]
                    ]
                ],
                false,
                Messages::RESOURCE_ID_MEMBER_IS_NOT_STRING
            ],
            'no valid errors object' => [
                [
                    Members::ERRORS => [
                        'wrong' => 'not valid'
                    ]
                ],
                false,
                Messages::ERRORS_OBJECT_NOT_ARRAY
            ],
            'no valid meta' => [
                [
                    Members::META => [
                        'key+' => 'not valid'
                    ],
                    Members::DATA => [
                        Members::TYPE => 'articles',
                        Members::ID => '1',
                        Members::ATTRIBUTES => [
                            Members::ERROR_TITLE => 'First'
                        ]
                    ]
                ],
                false,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ],
            'no valid jsonapi' => [
                [
                    Members::JSONAPI => [
                        Members::JSONAPI_VERSION => 1
                    ],
                    Members::DATA => [
                        Members::TYPE => 'articles',
                        Members::ID => '1',
                        Members::ATTRIBUTES => [
                            Members::ERROR_TITLE => 'First'
                        ]
                    ]
                ],
                false,
                Messages::JSONAPI_VERSION_IS_NOT_STRING
            ],
            'no valid included' => [
                [
                    Members::INCLUDED => [
                        'key' => 'not valid'
                    ],
                    Members::DATA => [
                        Members::TYPE => 'articles',
                        Members::ID => '1',
                        Members::ATTRIBUTES => [
                            Members::ERROR_TITLE => 'First'
                        ]
                    ]
                ],
                false,
                Messages::MUST_BE_ARRAY_OF_OBJECTS
            ],
            'bad value for top-level links member' => [
                [
                    Members::LINKS => [
                        Members::LINK_SELF => 'http://example.com/articles',
                        'forbidden' => 'not valid'
                    ],
                    Members::DATA => [
                        Members::TYPE => 'articles',
                        Members::ID => '1',
                        Members::ATTRIBUTES => [
                            Members::ERROR_TITLE => 'First'
                        ]
                    ]
                ],
                false,
                Messages::ONLY_ALLOWED_MEMBERS
            ]
        ];
    }

    /**
     * @test
     */
    public function topLevelLinksObjectIsValid()
    {
        $links = [
            Members::LINK_SELF => 'url'
        ];
        $strict = false;

        (new ValidateService())->validateTopLevelLinksMember($links, $strict);
        $this->succeed();
    }

    /**
     * @test
     * @dataProvider notValidTopLevelLinksObjectProvider
     */
    public function topLevelLinksObjectIsNotValid($json, $strict, $failureMessage)
    {
        $this->setFailure(ValidationException::class, $failureMessage, 400);
        (new ValidateService())->validateTopLevelLinksMember($json, $strict);
    }

    public function notValidTopLevelLinksObjectProvider()
    {
        return [
            'not allowed member' => [
                [
                    'anything' => 'not allowed'
                ],
                false,
                Messages::ONLY_ALLOWED_MEMBERS
            ]
        ];
    }
}
