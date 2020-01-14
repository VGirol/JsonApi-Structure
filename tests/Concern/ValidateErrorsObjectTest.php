<?php

declare(strict_types=1);

namespace VGirol\JsonApiStructure\Tests\Concern;

use VGirol\JsonApiConstant\Members;
use VGirol\JsonApiStructure\Exception\ValidationException;
use VGirol\JsonApiStructure\Messages;
use VGirol\JsonApiStructure\Tests\TestCase;
use VGirol\JsonApiStructure\ValidateService;

class ValidateErrorsObjectTest extends TestCase
{
    /**
     * @test
     */
    public function errorLinksObjectIsValid()
    {
        $json = [
            Members::LINK_ABOUT => 'url'
        ];
        $strict = false;

        (new ValidateService())->validateErrorLinksObject($json, $strict);
        $this->succeed();
    }

    /**
     * @test
     * @dataProvider notValidErrorLinksObjectProvider
     */
    public function errorLinksObjectIsNotValid($json, $strict, $failureMessage)
    {
        $this->setFailure(ValidationException::class, $failureMessage, 400);
        (new ValidateService())->validateErrorLinksObject($json, $strict);
    }

    public function notValidErrorLinksObjectProvider()
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
     * @dataProvider validErrorSourceObjectProvider
     */
    public function errorSourceObjectIsValid($json)
    {
        (new ValidateService())->validateErrorSourceObject($json);
        $this->succeed();
    }

    public function validErrorSourceObjectProvider()
    {
        return [
            'short' => [
                [
                    'anything' => 'blabla'
                ]
            ],
            'long' => [
                [
                    'anything' => 'blabla',
                    Members::ERROR_POINTER => '/data/attributes/title',
                    Members::ERROR_PARAMETER => 'blabla'
                ]
            ]
        ];
    }

    /**
     * @test
     * @dataProvider notValidErrorSourceObjectProvider
     */
    public function errorSourceObjectIsNotValid($json, $failureMessage)
    {
        $this->setFailure(ValidationException::class, $failureMessage, 400);
        (new ValidateService())->validateErrorSourceObject($json);
    }

    public function notValidErrorSourceObjectProvider()
    {
        return [
            'not an array' => [
                'error',
                Messages::ERROR_SOURCE_OBJECT_NOT_ARRAY
            ],
            'pointer is not a string' => [
                [
                    'valid' => 'valid',
                    Members::ERROR_POINTER => 666
                ],
                Messages::ERROR_SOURCE_POINTER_IS_NOT_STRING
            ],
            'pointer does not start with a /' => [
                [
                    'valid' => 'valid',
                    Members::ERROR_POINTER => 'not valid'
                ],
                Messages::ERROR_SOURCE_POINTER_START
            ],
            'parameter is not a string' => [
                [
                    'valid' => 'valid',
                    Members::ERROR_PARAMETER => 666
                ],
                Messages::ERROR_SOURCE_PARAMETER_IS_NOT_STRING
            ]
        ];
    }

    /**
     * @test
     */
    public function errorObjectIsValid()
    {
        $json = [
            Members::ID => 15,
            Members::LINKS => [
                Members::LINK_ABOUT => 'url'
            ],
            Members::ERROR_STATUS => 'test',
            Members::ERROR_CODE => 'E13',
            Members::ERROR_TITLE => 'test',
            Members::ERROR_DETAILS => 'test',
            Members::ERROR_SOURCE => [
                'anything' => 'valid',
                Members::ERROR_POINTER => '/data/type'
            ],
            Members::META => [
                'is valid' => 'because $strict is false'
            ]
        ];
        $strict = false;

        (new ValidateService())->validateErrorObject($json, $strict);
        $this->succeed();
    }

    /**
     * @test
     * @dataProvider notValidErrorObjectProvider
     */
    public function errorObjectIsNotValid($json, $strict, $failureMessage)
    {
        $this->setFailure(ValidationException::class, $failureMessage, 400);
        (new ValidateService())->validateErrorObject($json, $strict);
    }

    public function notValidErrorObjectProvider()
    {
        return [
            'not an array' => [
                'error',
                false,
                Messages::ERROR_OBJECT_NOT_ARRAY
            ],
            'empty array' => [
                [],
                false,
                Messages::ERROR_OBJECT_NOT_EMPTY
            ],
            'not allowed member' => [
                [
                    Members::ERROR_CODE => 'E13',
                    'not' => 'not valid',
                ],
                false,
                Messages::ONLY_ALLOWED_MEMBERS
            ],
            'status is not a string' => [
                [
                    Members::ERROR_CODE => 'E13',
                    Members::ERROR_STATUS => 666,
                ],
                false,
                Messages::ERROR_STATUS_IS_NOT_STRING
            ],
            'code is not a string' => [
                [
                    Members::ERROR_CODE => 13,
                    Members::ERROR_STATUS => 'ok',
                ],
                false,
                Messages::ERROR_CODE_IS_NOT_STRING
            ],
            'title is not a string' => [
                [
                    Members::ERROR_TITLE => 13,
                    Members::ERROR_STATUS => 'ok',
                ],
                false,
                Messages::ERROR_TITLE_IS_NOT_STRING
            ],
            'details is not a string' => [
                [
                    Members::ERROR_DETAILS => 13,
                    Members::ERROR_STATUS => 'ok',
                ],
                false,
                Messages::ERROR_DETAILS_IS_NOT_STRING
            ],
            'source is not an array' => [
                [
                    Members::ERROR_STATUS => 'ok',
                    Members::ERROR_SOURCE => 'not valid'
                ],
                false,
                Messages::ERROR_SOURCE_OBJECT_NOT_ARRAY
            ],
            'source pointer is not a string' => [
                [
                    Members::ERROR_STATUS => 'ok',
                    Members::ERROR_SOURCE => [
                        Members::ERROR_POINTER => 666
                    ]
                ],
                false,
                Messages::ERROR_SOURCE_POINTER_IS_NOT_STRING
            ],
            'source pointer is not valid' => [
                [
                    Members::ERROR_STATUS => 'ok',
                    Members::ERROR_SOURCE => [
                        Members::ERROR_POINTER => 'not valid'
                    ]
                ],
                false,
                Messages::ERROR_SOURCE_POINTER_START
            ],
            'source parameter is not a string' => [
                [
                    Members::ERROR_STATUS => 'ok',
                    Members::ERROR_SOURCE => [
                        Members::ERROR_PARAMETER => 666
                    ]
                ],
                false,
                Messages::ERROR_SOURCE_PARAMETER_IS_NOT_STRING
            ],
            'links is not valid' => [
                [
                    Members::ERROR_STATUS => 'ok',
                    Members::LINKS => [
                        'no' => 'not valid'
                    ]
                ],
                false,
                Messages::ONLY_ALLOWED_MEMBERS
            ],
            'meta is not valid' => [
                [
                    Members::ERROR_STATUS => 'ok',
                    Members::META => [
                        'not+' => 'not valid'
                    ]
                ],
                false,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ]
        ];
    }

    /**
     * @test
     */
    public function errorsObjectIsValid()
    {
        $json = [
            [
                Members::ERROR_STATUS => 'test',
                Members::ERROR_CODE => 'E13',
            ],
            [
                Members::ERROR_STATUS => 'test2',
                Members::ERROR_CODE => 'E132',
            ]
        ];
        $strict = false;

        (new ValidateService())->validateErrorsObject($json, $strict);
        $this->succeed();
    }

    /**
     * @test
     * @dataProvider notValidErrorsObjectProvider
     */
    public function errorsObjectIsNotValid($json, $strict, $failureMessage)
    {
        $this->setFailure(ValidationException::class, $failureMessage, 400);
        (new ValidateService())->validateErrorsObject($json, $strict);
    }

    public function notValidErrorsObjectProvider()
    {
        return [
            'not an array of objects' => [
                [
                    'error' => 'not valid'
                ],
                false,
                Messages::ERRORS_OBJECT_NOT_ARRAY
            ],
            'error object not valid' => [
                [
                    [
                        Members::ERROR_STATUS => 666,
                        Members::ERROR_CODE => 'E13'
                    ]
                ],
                false,
                Messages::ERROR_STATUS_IS_NOT_STRING
            ],
            'error object not safe' => [
                [
                    [
                        Members::ERROR_CODE => 'E13',
                        Members::META => [
                            'not valid' => 'not valid'
                        ]
                    ]
                ],
                true,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ]
        ];
    }
}
