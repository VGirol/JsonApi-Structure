<?php

namespace VGirol\JsonApiStructure\Tests\Service;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiStructure\Tests\TestCase;
use VGirol\JsonApiStructure\ValidateService;

class ValidateServiceTest extends TestCase
{
    /**
     * @test
     */
    public function setMethodViaConstructor()
    {
        $service = new ValidateService('post');

        PHPUnit::assertTrue($service->isPost());
    }

    /**
     * @test
     * @dataProvider setMethodProvider
     */
    public function setMethod($method)
    {
        $httpVerbs = [
            'isPost' => ['POST'],
            'isUpdate' => ['PATCH', 'PUT'],
            'isDelete' => ['DELETE']
        ];
        $service = new ValidateService();
        $obj = $service->setMethod($method);

        PHPUnit::assertSame($obj, $service);

        foreach ($httpVerbs as $fn => $verbs) {
            if (in_array(strtoupper($method), $verbs)) {
                PHPUnit::assertTrue($service->{$fn}());
            } else {
                PHPUnit::assertFalse($service->{$fn}());
            }
        }
    }

    public function setMethodProvider()
    {
        return [
            'post' => ['post'],
            'patch' => ['patch'],
            'put' => ['put'],
            'delete' => ['delete'],
        ];
    }

    /**
     * @test
     */
    public function setRouteType()
    {
        $service = new class extends ValidateService {
            public function mockIsFlagSet($flag)
            {
                return $this->isFlagSet($flag);
            }
        };
        $obj = $service->setRouteType(ValidateService::ROUTE_MAIN);

        PHPUnit::assertSame($obj, $service);
        PHPUnit::assertTrue($service->mockIsFlagSet(ValidateService::ROUTE_MAIN));
        PHPUnit::assertFalse($service->mockIsFlagSet(ValidateService::ROUTE_RELATED));
        PHPUnit::assertFalse($service->mockIsFlagSet(ValidateService::ROUTE_RELATIONSHIP));

        $service->setRouteType(ValidateService::ROUTE_RELATIONSHIP);

        PHPUnit::assertTrue($service->mockIsFlagSet(ValidateService::ROUTE_RELATIONSHIP));
        PHPUnit::assertFalse($service->mockIsFlagSet(ValidateService::ROUTE_RELATED));
        PHPUnit::assertFalse($service->mockIsFlagSet(ValidateService::ROUTE_MAIN));
    }
}
