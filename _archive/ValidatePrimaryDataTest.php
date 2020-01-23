<?php

namespace VGirol\JsonApiStructure\Tests\Concern;

use VGirol\JsonApiConstant\Members;
use VGirol\JsonApiStructure\Messages;
use VGirol\JsonApiStructure\Tests\TestCase;
use VGirol\JsonApiStructure\ValidateService;

class ValidatePrimaryDataTest extends TestCase
{
    /**
     * @test
     * @dataProvider validPrimaryDataProvider
     */
    public function primaryDataIsValid($json, $strict)
    {
        (new ValidateService())->validatePrimaryData($json, $strict);
        $this->succeed();
    }

    public function validPrimaryDataProvider()
    {
        return [
            'null' => [
                null,
                false
            ],
            'empty collection' => [
                [],
                false
            ],
            'resource identifier collection' => [
                [
                    [
                        Members::TYPE => 'test',
                        Members::ID => '2'
                    ],
                    [
                        Members::TYPE => 'test',
                        Members::ID => '3'
                    ]
                ],
                false
            ],
            'resource object collection' => [
                [
                    [
                        Members::TYPE => 'test',
                        Members::ID => '2',
                        Members::ATTRIBUTES => [
                            'attr' => 'test'
                        ]
                    ],
                    [
                        Members::TYPE => 'test',
                        Members::ID => '3',
                        Members::ATTRIBUTES => [
                            'attr' => 'another'
                        ]
                    ]
                ],
                false
            ],
            'unique resource identifier' => [
                [
                    Members::TYPE => 'test',
                    Members::ID => '2'
                ],
                false
            ],
            'unique resource object' => [
                [
                    Members::TYPE => 'test',
                    Members::ID => '2',
                    Members::ATTRIBUTES => [
                        'anything' => 'ok'
                    ]
                ],
                false
            ]
        ];
    }

    /**
     * @test
     * @dataProvider notValidPrimaryDataProvider
     */
    public function primaryDataIsNotValid($json, $strict, $failureMessage, $code)
    {
        $this->setFailure($failureMessage, $code);
        (new ValidateService())->validatePrimaryData($json, $strict);
    }

    public function notValidPrimaryDataProvider()
    {
        return [
            'not an array' => [
                'bad',
                false,
                sprintf(Messages::REQUEST_ERROR_DATA_MEMBER_NOT_ARRAY, gettype('bad')),
                403
            ],
            'collection with different type of resource objects' => [
                [
                    [
                        Members::TYPE => 'test',
                        Members::ID => '1'
                    ],
                    [
                        Members::TYPE => 'test',
                        Members::ID => '2',
                        Members::ATTRIBUTES => [
                            'anything' => 'valid'
                        ]
                    ]
                ],
                false,
                Messages::PRIMARY_DATA_SAME_TYPE,
                403
            ],
            'collection with not valid resource identifier objects' => [
                [
                    [
                        Members::TYPE => 'test',
                        Members::ID => '1'
                    ],
                    [
                        Members::TYPE => 'test',
                        Members::ID => '2',
                        'unvalid' => 'wrong'
                    ]
                ],
                false,
                Messages::ONLY_ALLOWED_MEMBERS,
                403
            ],
            'not safe meta member' => [
                [
                    Members::TYPE => 'test',
                    Members::ID => '2',
                    Members::ATTRIBUTES => [
                        'anything' => 'valid'
                    ],
                    Members::META => [
                        'not valid' => 'due to the blank character'
                    ]
                ],
                true,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS,
                403
            ]
        ];
    }
}
