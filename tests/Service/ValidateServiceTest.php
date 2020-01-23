<?php

namespace VGirol\JsonApiStructure\Tests\Service;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiConstant\Members;
use VGirol\JsonApiStructure\Messages\Messages;
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

    // /**
    //  * @test
    //  * @dataProvider requestDataHasNotValidTopLevelStructureProvider
    //  */
    // public function requestDataHasNotValidTopLevelStructure($method, $routeType, $isCollection, $content, $exceptionClass, $failureMsg)
    // {
    //     $service = new ValidateService($method, $routeType, $isCollection);

    //     $this->expectException($exceptionClass);
    //     $this->expectExceptionMessage($failureMsg);

    //     $service->validateRequestStructure($request);
    // }

    // public function requestDataHasNotValidTopLevelStructureProvider()
    // {
    //     return [
    //         'no data' => [
    //             'POST',
    //             ValidateService::ROUTE_RELATIONSHIP,
    //             false,
    //             [
    //                 Members::META => [
    //                     'key' => 'value'
    //                 ]
    //             ],
    //             JsonApi403Exception::class,
    //             Messages::REQUEST_ERROR_NO_DATA_MEMBER
    //         ],
    //         'no to-one relationship and data is null' => [
    //             'POST',
    //             ValidateService::ROUTE_RELATIONSHIP,
    //             false,
    //             [
    //                 Members::DATA => null
    //             ],
    //             JsonApi403Exception::class,
    //             Messages::REQUEST_ERROR_DATA_MEMBER_NULL
    //         ],
    //         'data is not an array' => [
    //             'POST',
    //             ValidateService::ROUTE_RELATIONSHIP,
    //             false,
    //             [
    //                 Members::DATA => 'bad'
    //             ],
    //             JsonApi403Exception::class,
    //             sprintf(Messages::REQUEST_ERROR_DATA_MEMBER_NOT_ARRAY, gettype('bad'))
    //         ],
    //         'collection expected' => [
    //             'POST',
    //             ValidateService::ROUTE_RELATIONSHIP,
    //             true,
    //             [
    //                 Members::DATA => [
    //                     Members::TYPE => 'type',
    //                     Members::ID => 'id'
    //                 ]
    //             ],
    //             JsonApi403Exception::class,
    //             Messages::REQUEST_ERROR_DATA_MEMBER_NOT_COLLECTION
    //         ],
    //         'single object expected' => [
    //             'POST',
    //             ValidateService::ROUTE_RELATIONSHIP,
    //             false,
    //             [
    //                 Members::DATA => [
    //                     [
    //                         Members::TYPE => 'type',
    //                         Members::ID => 'id'
    //                     ]
    //                 ]
    //             ],
    //             JsonApi403Exception::class,
    //             Messages::REQUEST_ERROR_DATA_MEMBER_NOT_SINGLE
    //         ]
    //     ];
    // }
}
