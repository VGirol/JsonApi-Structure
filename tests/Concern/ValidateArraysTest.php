<?php

namespace VGirol\JsonApiStructure\Tests\Asserts\Structure;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiStructure\Messages;
use VGirol\JsonApiStructure\Tests\TestCase;
use VGirol\JsonApiStructure\ValidateService;

class ValidateArraysTest extends TestCase
{
    /**
     * @test
     * @dataProvider arrayOfObjectsProvider
     */
    public function isArrayOfObjects($json)
    {
        $result = (new ValidateService())->isArrayOfObjects($json);

        PHPUnit::assertTrue($result);
    }

    public function arrayOfObjectsProvider()
    {
        return [
            'empty array' => [
                []
            ],
            'filled array' => [
                [
                    [
                        'key1' => 'value1'
                    ],
                    [
                        'key2' => 'value2'
                    ]
                ]
            ]
        ];
    }

    /**
     * @test
     */
    public function isArrayOfObjectsInvalidArgument()
    {
        $data = 'wrong';

        $this->setInvalidArgumentException(1, 'array', $data);
        (new ValidateService())->isArrayOfObjects($data);
    }

    /**
     * @test
     */
    public function isArrayOfObjectsFailed()
    {
        $json = [
            'key1' => 'value1',
            'key2' => 'value2'
        ];
        $result = (new ValidateService())->isArrayOfObjects($json);

        PHPUnit::assertFalse($result);
    }

    /**
     * @test
     * @dataProvider mustBeArrayOfObjectsFailedProvider
     */
    public function mustBeArrayOfObjectsFailed($data, $message, $failureMessage, $code)
    {
        $this->setFailure($failureMessage, $code);
        (new ValidateService())->mustBeArrayOfObjects($data, $message, $code);
    }

    public function mustBeArrayOfObjectsFailedProvider()
    {
        return [
            'default message' => [
                [
                    'key1' => 'value1',
                    'key2' => 'value2'
                ],
                '',
                Messages::MUST_BE_ARRAY_OF_OBJECTS,
                403
            ],
            'customized message' => [
                [
                    'key1' => 'value1',
                    'key2' => 'value2'
                ],
                'customized message',
                'customized message',
                403
            ]
        ];
    }

    /**
     * @test
     * @dataProvider notArrayOfObjectsProvider
     */
    public function isNotArrayOfObjects($json)
    {
        $result = (new ValidateService())->isNotArrayOfObjects($json);

        PHPUnit::assertTrue($result);
    }

    public function notArrayOfObjectsProvider()
    {
        return [
            'empty array' => [
                []
            ],
            'filled array' => [
                [
                    'key1' => 'value1',
                    'key2' => 'value2'
                ]
            ]
        ];
    }

    /**
     * @test
     */
    public function isNotArrayOfObjectsInvalidArgument()
    {
        $data = 'wrong';

        $this->setInvalidArgumentException(1, 'array', $data);
        (new ValidateService())->isNotArrayOfObjects($data);
    }

    /**
     * @test
     */
    public function isNotArrayOfObjectsFailed()
    {
        $data = [
            [
                'key1' => 'value1',
            ],
            [
                'key2' => 'value2'
            ]
        ];
        $result = (new ValidateService())->isNotArrayOfObjects($data);

        PHPUnit::assertFalse($result);
    }

    /**
     * @test
     * @dataProvider mustNotBeArrayOfObjectsFailedProvider
     */
    public function mustNotBeArrayOfObjectsFailed($data, $message, $failureMessage, $code)
    {
        $this->setFailure($failureMessage, $code);
        (new ValidateService())->mustNotBeArrayOfObjects($data, $message, $code);
    }

    public function mustNotBeArrayOfObjectsFailedProvider()
    {
        return [
            'default message' => [
                [
                    [
                        'key1' => 'value1'
                    ],
                    [
                        'key2' => 'value2'
                    ]
                ],
                '',
                Messages::MUST_NOT_BE_ARRAY_OF_OBJECTS,
                403
            ],
            'customized message' => [
                [
                    [
                        'key1' => 'value1'
                    ],
                    [
                        'key2' => 'value2'
                    ]
                ],
                'customized message',
                'customized message',
                403
            ]
        ];
    }
}
