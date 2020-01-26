<?php

namespace VGirol\JsonApiStructure\Tests\Service;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiStructure\CanUseDotPath;
use VGirol\JsonApiStructure\Exception\DotPathException;
use VGirol\JsonApiStructure\HaveBitwiseFlag;
use VGirol\JsonApiStructure\Tests\TestCase;

class HaveBitWiseFlagTest extends TestCase
{
    /**
     * @test
     */
    public function setAndGetFlag()
    {
        $mock = new class {
            use HaveBitwiseFlag;

            public function mockIsFlagSet($flag)
            {
                return $this->isFlagSet($flag);
            }

            public function mockSetFlag($flag, $value)
            {
                return $this->setFlag($flag, $value);
            }
        };

        $flag = 2;
        PHPUnit::assertFalse($mock->mockIsFlagSet($flag));

        $obj = $mock->mockSetFlag($flag, true);
        PHPUnit::assertSame($mock, $obj);
        PHPUnit::assertTrue($mock->mockIsFlagSet($flag));

        $mock->mockSetFlag($flag, false);
        PHPUnit::assertFalse($mock->mockIsFlagSet($flag));
    }

    /**
     * @test
     */
    public function selectFlag()
    {
        $mock = new class {
            use HaveBitwiseFlag;

            public function mockIsFlagSet($flag)
            {
                return $this->isFlagSet($flag);
            }

            public function mockSelectFlag($flag, $flags)
            {
                return $this->selectFlag($flag, $flags);
            }
        };

        $flag1 = 2;
        $flag2 = 4;
        $flag3 = 8;
        PHPUnit::assertFalse($mock->mockIsFlagSet($flag1));
        PHPUnit::assertFalse($mock->mockIsFlagSet($flag2));
        PHPUnit::assertFalse($mock->mockIsFlagSet($flag3));

        $obj = $mock->mockSelectFlag($flag2, [$flag1, $flag2, $flag3]);
        PHPUnit::assertSame($mock, $obj);
        PHPUnit::assertFalse($mock->mockIsFlagSet($flag1));
        PHPUnit::assertTrue($mock->mockIsFlagSet($flag2));
        PHPUnit::assertFalse($mock->mockIsFlagSet($flag3));

        $mock->mockSelectFlag($flag1, [$flag1, $flag2, $flag3]);
        PHPUnit::assertTrue($mock->mockIsFlagSet($flag1));
        PHPUnit::assertFalse($mock->mockIsFlagSet($flag2));
        PHPUnit::assertFalse($mock->mockIsFlagSet($flag3));
    }
}
