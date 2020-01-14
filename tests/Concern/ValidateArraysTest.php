<?php

declare(strict_types=1);

namespace VGirol\JsonApiStructure\Tests\Asserts\Structure;

use TypeError;
use VGirol\JsonApiStructure\Exception\ValidationException;
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
        (new ValidateService())->isArrayOfObjects($json);

        $this->succeed();
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
     * @dataProvider isArrayOfObjectsFailedProvider
     */
    public function isArrayOfObjectsFailed($data, $message, $exceptionClass, $failureMessage, $code)
    {
        $this->setFailure($exceptionClass, $failureMessage, $code);
        (new ValidateService())->isArrayOfObjects($data, $message);
    }

    public function isArrayOfObjectsFailedProvider()
    {
        return [
            'not an array' => [
                'wrong',
                '',
                TypeError::class,
                null,
                null
            ],
            'associative array' => [
                [
                    'key1' => 'value1',
                    'key2' => 'value2'
                ],
                '',
                ValidationException::class,
                Messages::MUST_BE_ARRAY_OF_OBJECTS,
                400
            ],
            'customized message' => [
                [
                    'key1' => 'value1',
                    'key2' => 'value2'
                ],
                'customized message',
                ValidationException::class,
                'customized message',
                400
            ]
        ];
    }

    /**
     * @test
     * @dataProvider notArrayOfObjectsProvider
     */
    public function isNotArrayOfObjects($json)
    {
        (new ValidateService())->isNotArrayOfObjects($json);

        $this->succeed();
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
     * @dataProvider isNotArrayOfObjectsFailedProvider
     */
    public function isNotArrayOfObjectsFailed($data, $message, $exceptionClass, $failureMessage, $code)
    {
        $this->setFailure($exceptionClass, $failureMessage, $code);

        (new ValidateService())->isNotArrayOfObjects($data, $message);
    }

    public function isNotArrayOfObjectsFailedProvider()
    {
        return [
            'not an array' => [
                'wrong',
                '',
                TypeError::class,
                null,
                null
            ],
            'array of objects' => [
                [
                    [
                        'key1' => 'value1',
                    ],
                    [
                        'key2' => 'value2'
                    ]
                ],
                '',
                ValidationException::class,
                Messages::MUST_NOT_BE_ARRAY_OF_OBJECTS,
                400
            ]
        ];
    }
}
