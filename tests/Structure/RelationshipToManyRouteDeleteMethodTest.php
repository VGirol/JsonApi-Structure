<?php

namespace VGirol\JsonApiStructure\Tests\Structure;

use VGirol\JsonApiConstant\Members;
use VGirol\JsonApiStructure\Messages;
use VGirol\JsonApiStructure\Tests\TestCase;
use VGirol\JsonApiStructure\ValidateService;

class RelationshipToManyRouteDeleteMethodTest extends TestCase
{
    /**
     * @test
     */
    public function dataIsValid()
    {
        $method = 'DELETE';
        $json = [
            Members::DATA => [
                [
                    Members::TYPE => 'resource',
                    Members::ID => 'id1'
                ],
                [
                    Members::TYPE => 'resource',
                    Members::ID => 'id2'
                ]
            ]
        ];
        $strict = true;
        $service = new ValidateService($method);
        $service->setRelationship(ValidateService::TO_MANY_RELATIONSHIP);

        $service->validateStructure($json, $strict);
        $this->succeed();
    }

    /**
     * @test
     * @dataProvider dataIsNotValidProvider
     */
    public function dataIsNotValid($json, $failureMsg, $code)
    {
        $method = 'DELETE';
        $strict = true;
        $service = new ValidateService($method);
        $service->setRelationship(ValidateService::TO_MANY_RELATIONSHIP);

        $this->setValidationFailure($failureMsg, $code);

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
            'data is null' => [
                [
                    Members::DATA => null
                ],
                Messages::REQUEST_ERROR_DATA_MEMBER_NULL,
                403
            ],
            'data is empty array' => [
                [
                    Members::DATA => []
                ],
                Messages::REQUEST_ERROR_DATA_MEMBER_NOT_COLLECTION,
                403
            ],
            'data is not a collection' => [
                [
                    Members::DATA => [
                        Members::TYPE => 'test',
                        Members::ID => 'id1'
                    ]
                ],
                Messages::REQUEST_ERROR_DATA_MEMBER_NOT_COLLECTION,
                403
            ],
            'data is a not a valid resource identifier collection' => [
                [
                    Members::DATA => [
                        [
                            Members::TYPE => 'resource',
                            Members::ID => 666
                        ]
                    ]
                ],
                Messages::RESOURCE_ID_MEMBER_MUST_BE_STRING,
                403
            ]
        ];
    }
}
