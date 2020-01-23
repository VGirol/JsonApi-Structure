<?php

namespace VGirol\JsonApiStructure\Tests\Concern;

use VGirol\JsonApiConstant\Members;
use VGirol\JsonApiStructure\Messages;
use VGirol\JsonApiStructure\Tests\TestCase;
use VGirol\JsonApiStructure\ValidateService;

class ValidateJsonapiObjectTest extends TestCase
{
    /**
     * @test
     */
    public function jsonapiObjectIsValid()
    {
        $json = [
            Members::JSONAPI_VERSION => 'jsonapi v1.1',
            Members::META => [
                'allowed' => 'valid'
            ]
        ];
        $strict = false;

        (new ValidateService)->validateJsonapiObject($json, $strict);
        $this->succeed();
    }

    /**
     * @test
     * @dataProvider notValidJsonapiObjectProvider
     */
    public function jsonapiObjectIsNotValid($json, $strict, $failureMessage, $code)
    {
        $this->setFailure($failureMessage, $code);
        (new ValidateService)->validateJsonapiObject($json, $strict);
    }

    public function notValidJsonapiObjectProvider()
    {
        return [
            'array of objects' => [
                [
                    [
                        Members::JSONAPI_VERSION => 'jsonapi 1.0'
                    ],
                    [
                        'not' => 'allowed'
                    ]
                ],
                false,
                Messages::OBJECT_NOT_ARRAY,
                403
            ],
            'not allowed member' => [
                [
                    Members::JSONAPI_VERSION => 'jsonapi 1.0',
                    'not' => 'allowed'
                ],
                false,
                Messages::ONLY_ALLOWED_MEMBERS,
                403
            ],
            'version is not a string' => [
                [
                    Members::JSONAPI_VERSION => 123
                ],
                false,
                Messages::JSONAPI_VERSION_IS_NOT_STRING,
                403
            ],
            'meta not valid' => [
                [
                    Members::JSONAPI_VERSION => 'jsonapi 1.0',
                    Members::META => [
                        'key+' => 'not valid'
                    ]
                ],
                false,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS,
                403
            ],
            'meta with not safe member' => [
                [
                    Members::JSONAPI_VERSION => 'jsonapi 1.0',
                    Members::META => [
                        'not safe' => 'not valid'
                    ]
                ],
                true,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS,
                403
            ]
        ];
    }
}
