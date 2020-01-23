<?php

namespace VGirol\JsonApiStructure\Tests\Constraints;

use VGirol\JsonApiStructure\Constraint\ContainsOnlyAllowedMembers;
use VGirol\JsonApiStructure\Exception\ValidationException;
use VGirol\JsonApiStructure\Tests\TestCase;

class ContainsOnlyAllowedMembersTest extends TestCase
{
    /**
     * @test
     */
    public function assertContainsOnlyAllowedMembers()
    {
        $allowed = ['anything', 'something'];

        $constraint = new ContainsOnlyAllowedMembers($allowed);

        $json = [
            'anything' => 'ok'
        ];
        $this->assertTrue($constraint->evaluate($json, '', true));
    }

    /**
     * @test
     * @dataProvider assertContainsOnlyAllowedMembersFailedProvider
     */
    public function assertContainsOnlyAllowedMembersFailed($json)
    {
        $allowed = ['anything', 'something'];

        $constraint = new ContainsOnlyAllowedMembers($allowed);

        $this->assertFalse($constraint->evaluate($json, '', true));
    }

    public function assertContainsOnlyAllowedMembersFailedProvider()
    {
        return [
            'not an array' => [
                'failed'
            ],
            'not only allowed members' => [
                [
                    'anything' => 'ok',
                    'notAllowed' => 'bad'
                ]
            ]
        ];
    }

    /**
     * @test
     */
    public function assertContainsOnlyAllowedMembersThrowsException()
    {
        $allowed = ['anything', 'something'];
        $json = [
            'anything' => 'ok',
            'notAllowed' => 'bad'
        ];

        $constraint = new ContainsOnlyAllowedMembers($allowed);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage($constraint->toString());
        $this->expectExceptionCode(403);

        $constraint->evaluate($json);
    }
}
