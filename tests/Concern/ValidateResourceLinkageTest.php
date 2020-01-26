<?php

namespace VGirol\JsonApiStructure\Tests\Concern;

use VGirol\JsonApiConstant\Members;
use VGirol\JsonApiStructure\Messages;
use VGirol\JsonApiStructure\Tests\TestCase;
use VGirol\JsonApiStructure\ValidateService;

class ValidateResourceLinkageTest extends TestCase
{
    /**
     * @test
     * @dataProvider validResourceLinkageProvider
     */
    public function resourceLinkageIsValid($json, $strict)
    {
        (new ValidateService())->validateResourceLinkage($json, $strict);
        $this->succeed();
    }

    public function validResourceLinkageProvider()
    {
        return [
            'null' => [
                null,
                false
            ],
            'empty array' => [
                [],
                false
            ],
            'single resource identifier object' => [
                [
                    Members::TYPE => 'people',
                    Members::ID => '9'
                ],
                false
            ],
            'array of resource identifier objects' => [
                [
                    [
                        Members::TYPE => 'people',
                        Members::ID => '9'
                    ],
                    [
                        Members::TYPE => 'people',
                        Members::ID => '10'
                    ]
                ],
                false
            ]
        ];
    }

    /**
     * @test
     * @dataProvider notValidResourceLinkageProvider
     */
    public function resourceLinkageIsNotValid($json, $strict, $failureMessage, $code)
    {
        $this->setFailure($failureMessage, $code);
        (new ValidateService())->validateResourceLinkage($json, $strict);
    }

    public function notValidResourceLinkageProvider()
    {
        return [
            'not an array' => [
                'not valid',
                false,
                Messages::RESOURCE_LINKAGE_BAD_TYPE,
                403
            ],
            'not valid single resource identifier object' => [
                [
                    Members::TYPE => 'people',
                    Members::ID => '9',
                    'anything' => 'not valid'
                ],
                false,
                Messages::ONLY_ALLOWED_MEMBERS,
                403
            ],
            'not valid array of resource identifier objects' => [
                [
                    [
                        Members::TYPE => 'people',
                        Members::ID => '9',
                        'anything' => 'not valid'
                    ],
                    [
                        Members::TYPE => 'people',
                        Members::ID => '10'
                    ]
                ],
                false,
                Messages::ONLY_ALLOWED_MEMBERS,
                403
            ],
            'not safe member name' => [
                [
                    [
                        Members::TYPE => 'people',
                        Members::ID => '9',
                        Members::META => [
                            'not valid' => 'due to the blank character'
                        ]
                    ]
                ],
                true,
                Messages::MEMBER_NAME_MUST_NOT_HAVE_RESERVED_CHARACTERS,
                403
            ]
        ];
    }

    /**
     * @test
     */
    public function resourceIdentifierObjectIsValid()
    {
        $json = [
            Members::ID => '1',
            Members::TYPE => 'test',
            Members::META => [
                'member' => 'is valid'
            ]
        ];
        $strict = false;

        (new ValidateService())->validateResourceIdentifierObject($json, $strict);
        $this->succeed();
    }

    /**
     * @test
     * @dataProvider isNotValidResourceIdentifierObjectProvider
     */
    public function resourceIdentifierObjectIsNotValid($json, $strict, $failureMessage, $code)
    {
        $this->setFailure($failureMessage, $code);
        (new ValidateService())->validateResourceIdentifierObject($json, $strict);
    }

    public function isNotValidResourceIdentifierObjectProvider()
    {
        return [
            'not an array' => [
                'failed',
                false,
                Messages::RESOURCE_IDENTIFIER_MUST_BE_ARRAY,
                403
            ],
            'id is missing' => [
                [
                    Members::TYPE => 'test'
                ],
                false,
                Messages::RESOURCE_ID_MEMBER_IS_ABSENT,
                403
            ],
            'id is not valid' => [
                [
                    Members::ID => 1,
                    Members::TYPE => 'test'
                ],
                false,
                Messages::RESOURCE_ID_MEMBER_MUST_BE_STRING,
                403
            ],
            'type is missing' => [
                [
                    Members::ID => '1'
                ],
                false,
                Messages::RESOURCE_TYPE_MEMBER_IS_ABSENT,
                403
            ],
            'type is not valid' => [
                [
                    Members::ID => '1',
                    Members::TYPE => 404
                ],
                false,
                Messages::RESOURCE_TYPE_MEMBER_MUST_BE_STRING,
                403
            ],
            'member not allowed' => [
                [
                    Members::ID => '1',
                    Members::TYPE => 'test',
                    'wrong' => 'wrong'
                ],
                false,
                Messages::ONLY_ALLOWED_MEMBERS,
                403
            ],
            'meta has not valid member name' => [
                [
                    Members::ID => '1',
                    Members::TYPE => 'test',
                    Members::META => [
                        'not valid' => 'due to the blank character'
                    ]
                ],
                true,
                Messages::MEMBER_NAME_MUST_NOT_HAVE_RESERVED_CHARACTERS,
                403
            ]
        ];
    }
}
