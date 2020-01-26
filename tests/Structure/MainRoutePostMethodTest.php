<?php

namespace VGirol\JsonApiStructure\Tests\Structure;

use VGirol\JsonApiConstant\Members;
use VGirol\JsonApiStructure\Messages;
use VGirol\JsonApiStructure\Tests\TestCase;
use VGirol\JsonApiStructure\ValidateService;

class MainRoutePostMethodTest extends TestCase
{
    /**
     * @test
     */
    public function dataIsValidSingleResource()
    {
        $method = 'POST';
        $strict = true;
        $json = [
            Members::DATA => [
                Members::TYPE => 'resource',
                Members::ID => 'id',
                Members::ATTRIBUTES => [
                    'attr1' =>'value1'
                ]
            ]
        ];
        $service = new ValidateService($method);
        $service->setSingle()
                ->setRouteType(ValidateService::ROUTE_MAIN);

        $service->validateStructure($json, $strict);
        $this->succeed();
    }

    /**
     * @test
     * @dataProvider dataIsNotValidSingleResourceProvider
     */
    public function dataIsNotValidSingleResource($json, $failureMsg, $code)
    {
        $method = 'POST';
        $strict = true;
        $service = new ValidateService($method);
        $service->setSingle()
                ->setRouteType(ValidateService::ROUTE_MAIN);

        $this->setFailure($failureMsg, $code);

        $service->validateStructure($json, $strict);
    }

    public function dataIsNotValidSingleResourceProvider()
    {
        return [
            'no data' => [
                [
                    Members::META => [
                        'key' => 'value'
                    ]
                ],
                Messages::REQUEST_ERROR_NO_DATA_MEMBER,
                403
            ],
            'data is null' => [
                [
                    Members::DATA => null
                ],
                Messages::REQUEST_ERROR_DATA_MEMBER_NULL,
                403
            ],
            'data is not an array' => [
                [
                    Members::DATA => 'error'
                ],
                sprintf(Messages::REQUEST_ERROR_DATA_MEMBER_NOT_ARRAY, gettype('error')),
                403
            ],
            'data is an empty array' => [
                [
                    Members::DATA => []
                ],
                Messages::REQUEST_ERROR_DATA_MEMBER_NOT_SINGLE,
                403
            ],
            'data is a collection' => [
                [
                    Members::DATA => [
                        [
                            Members::TYPE => 'test',
                            Members::ATTRIBUTES => [
                                'attr1' => 'value1'
                            ]
                        ],
                        [
                            Members::TYPE => 'test',
                            Members::ATTRIBUTES => [
                                'attr1' => 'value2'
                            ]
                        ]
                    ]
                ],
                Messages::REQUEST_ERROR_DATA_MEMBER_NOT_SINGLE,
                403
            ],
            'data is a not a valid resource object' => [
                [
                    Members::DATA => [
                        Members::TYPE => 666,
                        Members::ATTRIBUTES => [
                            'attr1' => 'value1'
                        ]
                    ]
                ],
                Messages::RESOURCE_TYPE_MEMBER_MUST_BE_STRING,
                403
            ],
            'data is a not a valid resource object (client-generated ID)' => [
                [
                    Members::DATA => [
                        Members::TYPE => 'test',
                        Members::ID => 666,
                        Members::ATTRIBUTES => [
                            'attr1' => 'value1'
                        ]
                    ]
                ],
                Messages::RESOURCE_ID_MEMBER_MUST_BE_STRING,
                403
            ],
            'data is a not a valid resource object (relationship without data)' => [
                [
                    Members::DATA => [
                        Members::TYPE => 'test',
                        Members::ATTRIBUTES => [
                            'attr1' => 'value1'
                        ],
                        Members::RELATIONSHIPS => [
                            'relation' => [
                                Members::META => [ 'key' => 'value' ]
                            ]
                        ]
                    ]
                ],
                Messages::RELATIONSHIP_NO_DATA_MEMBER,
                403
            ]
        ];
    }
}
