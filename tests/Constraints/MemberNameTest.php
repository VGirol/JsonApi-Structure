<?php

declare(strict_types=1);

namespace VGirol\JsonApiStructure\Tests\Constraints;

use VGirol\JsonApiStructure\Constraint\MemberName;
use VGirol\JsonApiStructure\Exception\ValidationException;
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

        $this->assertTrue($constraint->evaluate($json, '', true));
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
        $failureMessage = 'Member name is not valid.' . "\n" . $failureMessage;
        $constraint = new MemberName($strict);

        $this->setFailure(ValidationException::class, $failureMessage, $code);
        $constraint->evaluate($json, '', false);
    }

    public function notValidMemberNameProvider()
    {
        return [
            'not a string' => [
                123,
                false,
                Messages::MEMBER_NAME_IS_NOT_STRING,
                400
            ],
            'too short' => [
                '',
                false,
                Messages::MEMBER_NAME_IS_TOO_SHORT,
                400
            ],
            'strict mode' => [
                'not valid',
                true,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS,
                400
            ],
            'reserved characters' => [
                'az-F%3_t',
                false,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS,
                400
            ],
            'start with not globally allowed character' => [
                '_az',
                false,
                Messages::MEMBER_NAME_START_AND_END_WITH_ALLOWED_CHARACTERS,
                400
            ],
            'end with not globally allowed character' => [
                'az_',
                false,
                Messages::MEMBER_NAME_START_AND_END_WITH_ALLOWED_CHARACTERS,
                400
            ]
        ];
    }
}
