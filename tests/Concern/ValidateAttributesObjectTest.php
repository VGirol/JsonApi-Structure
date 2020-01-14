<?php

declare(strict_types=1);

namespace VGirol\JsonApiStructure\Tests\Concern;

use TypeError;
use VGirol\JsonApiConstant\Members;
use VGirol\JsonApiStructure\Exception\ValidationException;
use VGirol\JsonApiStructure\Messages;
use VGirol\JsonApiStructure\Tests\TestCase;
use VGirol\JsonApiStructure\ValidateService;

class ValidateAttributesObjectTest extends TestCase
{
    /**
     * @test
     */
    public function memberNameIsNotForbidden()
    {
        $name = 'valid';
        (new ValidateService())->isNotForbiddenMemberName($name);
        $this->succeed();
    }

    /**
     * @test
     * @dataProvider forbiddenMemberNameProvider
     */
    public function memberNameIsForbidden($name, $exceptionClass, $failureMessage, $code)
    {
        $this->setFailure($exceptionClass, $failureMessage, $code);
        (new ValidateService())->isNotForbiddenMemberName($name);
    }

    public function forbiddenMemberNameProvider()
    {
        return [
            'not a string' => [
                666,
                TypeError::class,
                null,
                null
            ],
            'member named "relationships"' => [
                Members::RELATIONSHIPS,
                ValidationException::class,
                Messages::MEMBER_NAME_NOT_ALLOWED,
                400
            ],
            'member named "links"' => [
                Members::LINKS,
                ValidationException::class,
                Messages::MEMBER_NAME_NOT_ALLOWED,
                400
            ]
        ];
    }

    /**
     * @test
     */
    public function fieldHasNoForbiddenMemberName()
    {
        $field = [
            'field' => 'valid'
        ];

        (new ValidateService())->fieldHasNoForbiddenMemberName($field);
        $this->succeed();
    }

    /**
     * @test
     * @dataProvider fieldHasForbiddenMemberNameProvider
     */
    public function fieldHasForbiddenMemberName($json, $failureMessage)
    {
        $this->setFailure(ValidationException::class, $failureMessage, 400);
        (new ValidateService())->fieldHasNoForbiddenMemberName($json);
    }

    public function fieldHasForbiddenMemberNameProvider()
    {
        return [
            'direct member' => [
                [
                    'anything' => 'ok',
                    Members::LINKS => 'forbidden'
                ],
                Messages::MEMBER_NAME_NOT_ALLOWED
            ],
            'nested member' => [
                [
                    'anything' => 'ok',
                    'something' => [
                        Members::LINKS => 'forbidden'
                    ]
                ],
                Messages::MEMBER_NAME_NOT_ALLOWED
            ]
        ];
    }

    /**
     * @test
     * @dataProvider validAttributesObjectProvider
     */
    public function attributesObjectIsValid($json, $strict)
    {
        (new ValidateService())->validateAttributesObject($json, $strict);
        $this->succeed();
    }

    public function validAttributesObjectProvider()
    {
        return [
            'strict' => [
                [
                    'strict' => 'value'
                ],
                true
            ],
            'not strict' => [
                [
                    'not strict' => 'value'
                ],
                false
            ]
        ];
    }

    /**
     * @test
     * @dataProvider notValidAttributesObjectProvider
     */
    public function attributesObjectIsNotValid($json, $strict, $exceptionClass, $failureMessage, $code)
    {
        $this->setFailure($exceptionClass, $failureMessage, $code);
        (new ValidateService())->validateAttributesObject($json, $strict);
    }

    public function notValidAttributesObjectProvider()
    {
        return [
            'not an array' => [
                'error',
                false,
                TypeError::class,
                null,
                null
            ],
            'key is not valid' => [
                [
                    'key+' => 'value'
                ],
                false,
                ValidationException::class,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS,
                400
            ],
            'key is not safe' => [
                [
                    'not safe' => 'value'
                ],
                true,
                ValidationException::class,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS,
                400
            ],
            'field has forbidden member' => [
                [
                    'key' => [
                        'obj' => 'value',
                        Members::LINKS => 'forbidden'
                    ]
                ],
                false,
                ValidationException::class,
                Messages::MEMBER_NAME_NOT_ALLOWED,
                400
            ],
            'is array of objects' => [
                [
                    [
                        'attr1' => 'value1',
                    ],
                    [
                        'attr2' => 'value2',
                    ]
                ],
                false,
                ValidationException::class,
                Messages::ATTRIBUTES_OBJECT_IS_NOT_ARRAY,
                400
            ]
        ];
    }

    // /**
    //  * @test
    //  */
    // public function assertIsValidAttributesObjectWithInvalidArguments()
    // {
    //     $attributes = 'failed';
    //     $strict = false;

    //     $this->setInvalidArgumentException(1, 'array', $attributes);

    //     JsonApiAssert::assertIsValidAttributesObject($attributes, $strict);
    // }
}
