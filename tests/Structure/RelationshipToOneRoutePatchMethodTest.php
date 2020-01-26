<?php

namespace VGirol\JsonApiStructure\Tests\Structure;

use VGirol\JsonApiConstant\Members;
use VGirol\JsonApiStructure\Messages;
use VGirol\JsonApiStructure\Tests\TestCase;
use VGirol\JsonApiStructure\ValidateService;

class RelationshipToOneRoutePatchMethodTest extends TestCase
{
    /**
     * @test
     * @dataProvider dataIsValidProvider
     */
    public function dataIsValid($json)
    {
        $method = 'PATCH';
        $strict = true;
        $service = new ValidateService($method);
        $service->setRelationship(ValidateService::TO_ONE_RELATIONSHIP);

        $service->validateStructure($json, $strict);
        $this->succeed();
    }

    public function dataIsValidProvider()
    {
        return [
            'data is valid resource identifier' => [
                [
                    Members::DATA => [
                        Members::TYPE => 'resource',
                        Members::ID => 'id'
                    ]
                ]
            ],
            'data is null' => [
                [
                    Members::DATA => null
                ]
            ]
        ];
    }

    /**
     * @test
     * @dataProvider dataIsNotValidProvider
     */
    public function dataIsNotValid($json, $failureMsg, $code)
    {
        $method = 'PATCH';
        $strict = true;
        $service = new ValidateService($method);
        $service->setRelationship(ValidateService::TO_ONE_RELATIONSHIP);

        $this->setFailure($failureMsg, $code);

        $service->validateStructure($json, $strict);
    }

    public function dataIsNotValidProvider()
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
                            Members::ID => 'id1'
                        ],
                        [
                            Members::TYPE => 'test',
                            Members::ID => 'id2'
                        ]
                    ]
                ],
                Messages::REQUEST_ERROR_DATA_MEMBER_NOT_SINGLE,
                403
            ],
            'data is a not a valid resource identifier object' => [
                [
                    Members::DATA => [
                        Members::TYPE => 'resource',
                        Members::ID => 666
                    ]
                ],
                Messages::RESOURCE_ID_MEMBER_MUST_BE_STRING,
                403
            ]
        ];
    }
}
