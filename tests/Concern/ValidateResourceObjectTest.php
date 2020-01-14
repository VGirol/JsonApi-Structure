<?php

declare(strict_types=1);

namespace VGirol\JsonApiStructure\Tests\Concern;

use VGirol\JsonApiConstant\Members;
use VGirol\JsonApiStructure\Exception\ValidationException;
use VGirol\JsonApiStructure\Messages;
use VGirol\JsonApiStructure\Tests\TestCase;
use VGirol\JsonApiStructure\ValidateService;

class ValidateResourceObjectTest extends TestCase
{
    /**
     * @test
     */
    public function resourceFieldNameIsNotForbidden()
    {
        $name = 'test';

        (new ValidateService())->isNotForbiddenResourceFieldName($name);
        $this->succeed();
    }

    /**
     * @test
     * @dataProvider resourceFieldNameIsForbiddenProvider
     */
    public function resourceFieldNameIsForbidden($name, $failureMessage)
    {
        $this->setFailure(ValidationException::class, $failureMessage, 400);
        (new ValidateService())->isNotForbiddenResourceFieldName($name);
    }

    public function resourceFieldNameIsForbiddenProvider()
    {
        return [
            Members::TYPE => [
                Members::TYPE,
                Messages::FIELDS_NAME_NOT_ALLOWED
            ],
            Members::ID => [
                Members::ID,
                Messages::FIELDS_NAME_NOT_ALLOWED
            ]
        ];
    }

    /**
     * @test
     */
    public function resourceLinksObjectIsValid()
    {
        $links = [
            Members::LINK_SELF => 'url'
        ];
        $strict = false;

        (new ValidateService())->validateResourceLinksObject($links, $strict);
        $this->succeed();
    }

    /**
     * @test
     * @dataProvider notValidResourceLinksObjectProvider
     */
    public function resourceLinksObjectIsNotValid($json, $strict, $failureMessage)
    {
        $this->setFailure(ValidationException::class, $failureMessage, 400);
        (new ValidateService())->validateResourceLinksObject($json, $strict);
    }

    public function notValidResourceLinksObjectProvider()
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

    /**
     * @test
     */
    public function resourceHasValidTopLevelStructure()
    {
        $json = [
            Members::ID => '1',
            Members::TYPE => 'articles',
            Members::ATTRIBUTES => [
                'attr' => 'test'
            ],
            Members::LINKS => [
                Members::LINK_SELF => '/articles/1'
            ],
            Members::META => [
                'member' => 'is valid'
            ],
            Members::RELATIONSHIPS => [
                'author' => [
                    Members::LINKS => [
                        Members::LINK_SELF => '/articles/1/relationships/author',
                        Members::LINK_RELATED => '/articles/1/author'
                    ],
                    Members::DATA => [
                        Members::TYPE => 'people',
                        Members::ID => '9'
                    ]
                ]
            ]
        ];
        $strict = true;

        (new ValidateService())->validateResourceObjectTopLevelStructure($json, $strict);
        $this->succeed();
    }

    /**
     * @test
     * @dataProvider hasNotValidTopLevelStructureProvider
     */
    public function resourceHasNotValidTopLevelStructure($json, $failureMessage)
    {
        $strict = true;
        $this->setFailure(ValidationException::class, $failureMessage, 400);
        (new ValidateService())->validateResourceObjectTopLevelStructure($json, $strict);
    }

    public function hasNotValidTopLevelStructureProvider()
    {
        return [
            'not an array' => [
                'failed',
                Messages::RESOURCE_IS_NOT_ARRAY
            ],
            'id is missing' => [
                [
                    Members::TYPE => 'test',
                    Members::ATTRIBUTES => [
                        'attr' => 'value'
                    ]
                ],
                Messages::RESOURCE_ID_MEMBER_IS_ABSENT
            ],
            'type is missing' => [
                [
                    Members::ID => '1',
                    Members::ATTRIBUTES => [
                        'attr' => 'value'
                    ]
                ],
                Messages::RESOURCE_TYPE_MEMBER_IS_ABSENT
            ],
            'missing mandatory member' => [
                [
                    Members::ID => '1',
                    Members::TYPE => 'test'
                ],
                sprintf(
                    Messages::CONTAINS_AT_LEAST_ONE,
                    implode(', ', [Members::ATTRIBUTES, Members::RELATIONSHIPS, Members::LINKS, Members::META])
                )
            ],
            'member not allowed' => [
                [
                    Members::ID => '1',
                    Members::TYPE => 'test',
                    Members::META => [
                        'anything' => 'good'
                    ],
                    'wrong' => 'wrong'
                ],
                Messages::ONLY_ALLOWED_MEMBERS
            ]
        ];
    }

    /**
     * @test
     */
    public function resourceIdMemberIsValid()
    {
        $json = [
            Members::ID => '1',
            Members::TYPE => 'test'
        ];

        (new ValidateService())->validateResourceIdMember($json);
        $this->succeed();
    }

    /**
     * @test
     * @dataProvider notValidResourceIdMemberProvider
     */
    public function resourceIdMemberIsNotValid($json, $failureMessage)
    {
        $this->setFailure(ValidationException::class, $failureMessage, 400);
        (new ValidateService())->validateResourceIdMember($json);
    }

    public function notValidResourceIdMemberProvider()
    {
        return [
            'id is empty' => [
                [
                    Members::ID => '',
                    Members::TYPE => 'test'
                ],
                Messages::RESOURCE_ID_MEMBER_IS_EMPTY
            ],
            'id is not a string' => [
                [
                    Members::ID => 1,
                    Members::TYPE => 'test'
                ],
                Messages::RESOURCE_ID_MEMBER_IS_NOT_STRING
            ]
        ];
    }

    /**
     * @test
     */
    public function resourceTypeMemberIsValid()
    {
        $json = [
            Members::ID => '1',
            Members::TYPE => 'test'
        ];
        $strict = false;

        (new ValidateService())->validateResourceTypeMember($json, $strict);
        $this->succeed();
    }

    /**
     * @test
     * @dataProvider notValidResourceTypeMemberProvider
     */
    public function resourceTypeMemberIsNotValid($json, $strict, $failureMessage)
    {
        $this->setFailure(ValidationException::class, $failureMessage, 400);
        (new ValidateService())->validateResourceTypeMember($json, $strict);
    }

    public function notValidResourceTypeMemberProvider()
    {
        return [
            'type is empty' => [
                [
                    Members::ID => '1',
                    Members::TYPE => ''
                ],
                false,
                Messages::RESOURCE_TYPE_MEMBER_IS_EMPTY
            ],
            'type is not a string' => [
                [
                    Members::ID => '1',
                    Members::TYPE => 404
                ],
                false,
                Messages::RESOURCE_TYPE_MEMBER_IS_NOT_STRING
            ],
            'type value has forbidden characters' => [
                [
                    Members::ID => '1',
                    Members::TYPE => 'test+1'
                ],
                false,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ],
            'type value has not safe characters' => [
                [
                    Members::ID => '1',
                    Members::TYPE => 'test 1'
                ],
                true,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ]
        ];
    }

    /**
     * @test
     */
    public function resourceFieldIsValid()
    {
        $json = [
            Members::ID => '1',
            Members::TYPE => 'articles',
            Members::ATTRIBUTES => [
                'attr' => 'test'
            ],
            Members::RELATIONSHIPS => [
                'author' => [
                    Members::DATA => [
                        Members::TYPE => 'people',
                        Members::ID => '9'
                    ]
                ]
            ]
        ];

        (new ValidateService())->validateFields($json);
        $this->succeed();
    }

    /**
     * @test
     * @dataProvider isNotValidResourceFieldProvider
     */
    public function resourceFieldIsNotValid($json, $failureMessage)
    {
        $this->setFailure(ValidationException::class, $failureMessage, 400);
        (new ValidateService())->validateFields($json);
    }

    public function isNotValidResourceFieldProvider()
    {
        return [
            'attribute and relationship with the same name' => [
                [
                    Members::ID => '1',
                    Members::TYPE => 'articles',
                    Members::ATTRIBUTES => [
                        'anonymous' => 'test'
                    ],
                    Members::RELATIONSHIPS => [
                        'anonymous' => [
                            Members::DATA => [
                                Members::TYPE => 'people',
                                Members::ID => '9'
                            ]
                        ]
                    ]
                ],
                Messages::FIELDS_HAVE_SAME_NAME
            ],
            'attribute named type or id' => [
                [
                    Members::ID => '1',
                    Members::TYPE => 'articles',
                    Members::ATTRIBUTES => [
                        'attr' => 'test',
                        Members::ID => 'not valid'
                    ]
                ],
                Messages::FIELDS_NAME_NOT_ALLOWED
            ],
            'relationship named type or id' => [
                [
                    Members::ID => '1',
                    Members::TYPE => 'articles',
                    Members::ATTRIBUTES => [
                        'attr' => 'test'
                    ],
                    Members::RELATIONSHIPS => [
                        Members::TYPE => [
                            Members::DATA => [
                                Members::TYPE => 'people',
                                Members::ID => '9'
                            ]
                        ]
                    ]
                ],
                Messages::FIELDS_NAME_NOT_ALLOWED
            ]
        ];
    }

    /**
     * @test
     */
    public function resourceObjectIsValid()
    {
        $json = [
            Members::ID => '1',
            Members::TYPE => 'articles',
            Members::ATTRIBUTES => [
                'attr' => 'test'
            ],
            Members::LINKS => [
                Members::LINK_SELF => '/articles/1'
            ],
            Members::META => [
                'member' => 'is valid'
            ],
            Members::RELATIONSHIPS => [
                'author' => [
                    Members::LINKS => [
                        Members::LINK_SELF => '/articles/1/relationships/author',
                        Members::LINK_RELATED => '/articles/1/author'
                    ],
                    Members::DATA => [
                        Members::TYPE => 'people',
                        Members::ID => '9'
                    ]
                ]
            ]
        ];
        $strict = false;

        (new ValidateService())->validateResourceObject($json, $strict);
        $this->succeed();
    }

    /**
     * @test
     * @dataProvider isNotValidResourceObjectProvider
     */
    public function resourceObjectIsNotValid($json, $strict, $failureMessage)
    {
        $this->setFailure(ValidationException::class, $failureMessage, 400);
        (new ValidateService())->validateResourceObject($json, $strict);
    }

    public function isNotValidResourceObjectProvider()
    {
        return [
            'not an array' => [
                'failed',
                false,
                Messages::RESOURCE_IS_NOT_ARRAY
            ],
            'id is not valid' => [
                [
                    Members::ID => 1,
                    Members::TYPE => 'test',
                    Members::ATTRIBUTES => [
                        'attr' => 'test'
                    ]
                ],
                false,
                Messages::RESOURCE_ID_MEMBER_IS_NOT_STRING
            ],
            'type is not valid' => [
                [
                    Members::ID => '1',
                    Members::TYPE => 404,
                    Members::ATTRIBUTES => [
                        'attr' => 'test'
                    ]
                ],
                false,
                Messages::RESOURCE_TYPE_MEMBER_IS_NOT_STRING
            ],
            'missing mandatory member' => [
                [
                    Members::ID => '1',
                    Members::TYPE => 'test'
                ],
                false,
                sprintf(
                    Messages::CONTAINS_AT_LEAST_ONE,
                    implode(', ', [Members::ATTRIBUTES, Members::RELATIONSHIPS, Members::LINKS, Members::META])
                )
            ],
            'member not allowed' => [
                [
                    Members::ID => '1',
                    Members::TYPE => 'test',
                    Members::ATTRIBUTES => [
                        'attr' => 'test'
                    ],
                    'wrong' => 'wrong'
                ],
                false,
                Messages::ONLY_ALLOWED_MEMBERS
            ],
            'attributes not valid' => [
                [
                    Members::ID => '1',
                    Members::TYPE => 'test',
                    Members::ATTRIBUTES => [
                        'attr' => 'test',
                        'key+' => 'wrong'
                    ]
                ],
                false,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ],
            'fields not valid (attribute and relationship with the same name)' => [
                [
                    Members::ID => '1',
                    Members::TYPE => 'articles',
                    Members::ATTRIBUTES => [
                        'anonymous' => 'test'
                    ],
                    Members::RELATIONSHIPS => [
                        'anonymous' => [
                            Members::DATA => [
                                Members::TYPE => 'people',
                                Members::ID => '9'
                            ]
                        ]
                    ]
                ],
                false,
                Messages::FIELDS_HAVE_SAME_NAME
            ],
            'fields not valid (attribute named "type" or "id")' => [
                [
                    Members::ID => '1',
                    Members::TYPE => 'articles',
                    Members::ATTRIBUTES => [
                        'attr' => 'test',
                        Members::ID => 'not valid'
                    ]
                ],
                false,
                Messages::FIELDS_NAME_NOT_ALLOWED
            ],
            'relationship not valid' => [
                [
                    Members::ID => '1',
                    Members::TYPE => 'articles',
                    Members::ATTRIBUTES => [
                        'attr' => 'test'
                    ],
                    Members::RELATIONSHIPS => [
                        'author' => [
                            Members::DATA => [
                                Members::TYPE => 'people',
                                Members::ID => '9',
                                'wrong' => 'not valid'
                            ]
                        ]
                    ]
                ],
                false,
                Messages::ONLY_ALLOWED_MEMBERS
            ],
            'meta with not safe member name' => [
                [
                    Members::ID => '1',
                    Members::TYPE => 'articles',
                    Members::ATTRIBUTES => [
                        'attr' => 'test'
                    ],
                    Members::META => [
                        'not valid' => 'due to the blank character'
                    ]
                ],
                true,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ],
            'links not valid' => [
                [
                    Members::ID => '1',
                    Members::TYPE => 'articles',
                    Members::ATTRIBUTES => [
                        'attr' => 'test'
                    ],
                    Members::LINKS => [
                        'not valid' => 'bad'
                    ]
                ],
                true,
                Messages::ONLY_ALLOWED_MEMBERS
            ]
        ];
    }

    /**
     * @test
     */
    public function emptyResourceObjectCollectionIsValid()
    {
        $json = [];
        $strict = false;

        (new ValidateService())->validateResourceObjectCollection($json, $strict);
        $this->succeed();
    }

    /**
     * @test
     */
    public function resourceObjectCollectionIsValid()
    {
        $json = [];
        for ($i = 1; $i < 3; $i++) {
            $json[] = [
                Members::ID => (string) $i,
                Members::TYPE => 'articles',
                Members::ATTRIBUTES => [
                    'attr' => 'test'
                ]
            ];
        }
        $strict = false;

        (new ValidateService())->validateResourceObjectCollection($json, $strict);
        $this->succeed();
    }

    /**
     * @test
     * @dataProvider resourceObjectCollectionIsNotValidProvider
     */
    public function resourceObjectCollectionIsNotValid($json, $strict, $failureMessage)
    {
        $this->setFailure(ValidationException::class, $failureMessage, 400);
        (new ValidateService())->validateResourceObjectCollection($json, $strict);
    }

    public function resourceObjectCollectionIsNotValidProvider()
    {
        return [
            'not an array' => [
                'failed',
                false,
                Messages::RESOURCE_COLLECTION_NOT_ARRAY
            ],
            'not an array of objects' => [
                [
                    Members::ID => '1',
                    Members::TYPE => 'articles',
                    Members::ATTRIBUTES => [
                        'attr' => 'test'
                    ]
                ],
                false,
                Messages::MUST_BE_ARRAY_OF_OBJECTS
            ],
            'not valid collection' => [
                [
                    [
                        Members::ID => 1,
                        Members::TYPE => 'articles',
                        Members::ATTRIBUTES => [
                            'attr' => 'test'
                        ]
                    ]
                ],
                false,
                Messages::RESOURCE_ID_MEMBER_IS_NOT_STRING
            ]
        ];
    }
}
