<?php

namespace VGirol\JsonApiStructure\Tests\Constraints;

use VGirol\JsonApiStructure\Constraint\Constraint;
use VGirol\JsonApiStructure\Tests\TestCase;

class ConstraintsTest extends TestCase
{
    // /**
    //  * @test
    //  */
    // public function evaluateReturnsValue()
    // {
    //     $mock = $this->getMockForAbstractClass(Constraint::class);
    //     $mock->expects($this->any())
    //          ->method('handle')
    //          ->will($this->returnValue(false));

    //     $result = $mock->evaluate('test', 'description', true);

    //     $this->assertFalse($result);
    // }

    // /**
    //  * @test
    //  */
    // public function evaluateReturnsValueWhileNotAsked()
    // {
    //     $mock = $this->getMockForAbstractClass(Constraint::class);
    //     $mock->expects($this->any())
    //          ->method('handle')
    //          ->will($this->returnValue(true));

    //     $result = $mock->evaluate('test', 'description', false);

    //     $this->assertTrue($result);
    // }

    /**
     * @test
     */
    public function evaluateFailedWhithDefaultMessageAndCode()
    {
        $obj = new class extends Constraint {
            public function default(): string
            {
                return 'toString() message.';
            }

            public function handle($inspected): bool
            {
                return false;
            }
        };

        $this->setFailure("toString() message.", 403);

        $obj->evaluate('test', '');
    }

    /**
     * @test
     */
    public function evaluateFailedWhithFailureMessageAndDescriptionAndCustomCode()
    {
        $obj = new class extends Constraint {
            public function default(): string
            {
                return 'toString() message.';
            }

            public function handle($inspected): bool
            {
                $this->setFailureMessage('Failure message.');
                return false;
            }
        };

        $this->setFailure("toString() message.\nFailure message.\nDescription", 401);

        $obj->evaluate('test', 'Description', 401);
    }
}
