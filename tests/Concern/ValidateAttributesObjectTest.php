<?php

namespace VGirol\JsonApiStructure\Tests\Concern;

use VGirol\JsonApiConstant\Members;
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
     */
    public function memberNameIsNotForbiddenFailed()
    {
        $name = 666;

        $this->setInvalidArgumentException(1, 'string', $name);
        (new ValidateService())->isNotForbiddenMemberName($name);
    }

    /**
     * @test
     * @dataProvider forbiddenMemberNameProvider
     */
    public function memberNameIsForbidden($name, $failureMessage, $code)
    {
        $this->setValidationFailure($failureMessage, $code);
        (new ValidateService())->isNotForbiddenMemberName($name);
    }

    public function forbiddenMemberNameProvider()
    {
        return [
            'member named "relationships"' => [
                Members::RELATIONSHIPS,
                Messages::MEMBER_NAME_NOT_ALLOWED,
                403
            ],
            'member named "links"' => [
                Members::LINKS,
                Messages::MEMBER_NAME_NOT_ALLOWED,
                403
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
        $this->setValidationFailure($failureMessage, 403);
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
     */
    public function validateAttributesObjectWithInvalidArguments()
    {
        $json = 'failed';
        $strict = false;

        $this->setInvalidArgumentException(1, 'array', $json);

        (new ValidateService())->validateAttributesObject($json, $strict);
    }

    /**
     * @test
     * @dataProvider notValidAttributesObjectProvider
     */
    public function attributesObjectIsNotValid($json, $strict, $failureMessage, $code)
    {
        $this->setValidationFailure($failureMessage, $code);
        (new ValidateService())->validateAttributesObject($json, $strict);
    }

    public function notValidAttributesObjectProvider()
    {
        return [
            'key is not valid' => [
                [
                    'key+' => 'value'
                ],
                false,
                Messages::MEMBER_NAME_MUST_NOT_HAVE_RESERVED_CHARACTERS,
                403
            ],
            'key is not safe' => [
                [
                    'not safe' => 'value'
                ],
                true,
                Messages::MEMBER_NAME_MUST_NOT_HAVE_RESERVED_CHARACTERS,
                403
            ],
            'field has forbidden member' => [
                [
                    'key' => [
                        'obj' => 'value',
                        Members::LINKS => 'forbidden'
                    ]
                ],
                false,
                Messages::MEMBER_NAME_NOT_ALLOWED,
                403
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
                Messages::ATTRIBUTES_OBJECT_MUST_BE_ARRAY,
                403
            ]
        ];
    }
}
