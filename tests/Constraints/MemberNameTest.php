<?php

namespace VGirol\JsonApiStructure\Tests\Constraints;

use VGirol\JsonApiStructure\Constraint\MemberName;
use VGirol\JsonApiStructure\Messages;
use VGirol\JsonApiStructure\Tests\TestCase;

class MemberNameTest extends TestCase
{
    /**
     * @test
     * @dataProvider validMemberNameProvider
     */
    public function memberNameIsValid($json, $strict)
    {
        $constraint = new MemberName($strict);

        $this->assertTrue($constraint->handle($json));
    }

    public function validMemberNameProvider()
    {
        return [
            'not strict' => [
                'valid member',
                false
            ],
            'strict' => [
                'valid-member',
                true
            ],
            'short' => [
                'a',
                true
            ]
        ];
    }

    /**
     * @test
     * @dataProvider notValidMemberNameProvider
     */
    public function memberNameIsNotValid($json, $strict, $failureMessage, $code)
    {
        $failureMessage = Messages::MEMBER_NAME_NOT_VALID . "\n" . $failureMessage;
        $constraint = new MemberName($strict);

        $this->setValidationFailure($failureMessage, $code);
        $constraint->evaluate($json);
    }

    public function notValidMemberNameProvider()
    {
        return [
            'not a string' => [
                123,
                false,
                Messages::MEMBER_NAME_MUST_BE_STRING,
                403
            ],
            'too short' => [
                '',
                false,
                Messages::MEMBER_NAME_IS_TOO_SHORT,
                403
            ],
            'strict mode' => [
                'not valid',
                true,
                Messages::MEMBER_NAME_MUST_NOT_HAVE_RESERVED_CHARACTERS,
                403
            ],
            'reserved characters' => [
                'az-F%3_t',
                false,
                Messages::MEMBER_NAME_MUST_NOT_HAVE_RESERVED_CHARACTERS,
                403
            ],
            'start with not globally allowed character' => [
                '_az',
                false,
                Messages::MEMBER_NAME_MUST_START_AND_END_WITH_ALLOWED_CHARACTERS,
                403
            ],
            'end with not globally allowed character' => [
                'az_',
                false,
                Messages::MEMBER_NAME_MUST_START_AND_END_WITH_ALLOWED_CHARACTERS,
                403
            ]
        ];
    }
}
