<?php

namespace VGirol\JsonApiStructure\Tests\Constraints;

use VGirol\JsonApiStructure\Constraint\ContainsAtLeastOne;
use VGirol\JsonApiStructure\Exception\ValidationException;
use VGirol\JsonApiStructure\Tests\TestCase;

class ContainsAtLeastOneTest extends TestCase
{
    /**
     * @test
     */
    public function assertContainsAtLeastOne()
    {
        $allowed = ['anything', 'something'];

        $constraint = new ContainsAtLeastOne($allowed);

        $json = [
            'anything' => 'ok',
            'another' => 'ok'
        ];
        $this->assertTrue($constraint->handle($json));
    }

    /**
     * @test
     * @dataProvider assertContainsAtLeastOneFailedProvider
     */
    public function assertContainsAtLeastOneFailed($json)
    {
        $allowed = ['anything', 'something'];

        $constraint = new ContainsAtLeastOne($allowed);

        $this->assertFalse($constraint->handle($json));
    }

    public function assertContainsAtLeastOneFailedProvider()
    {
        return [
            'not an array' => [
                'failed'
            ],
            'no one expected member' => [
                [
                    'unexpected' => 'bad'
                ]
            ]
        ];
    }

    /**
     * @test
     */
    public function assertContainsAtLeastOneFailedAndThrowException()
    {
        $allowed = ['anything', 'something'];
        $json = [
            'unexpected' => 'bad'
        ];

        $constraint = new ContainsAtLeastOne($allowed);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage($constraint->toString());
        $this->expectExceptionCode(403);

        $constraint->evaluate($json);
    }
}
