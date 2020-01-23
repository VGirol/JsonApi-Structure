<?php

namespace VGirol\JsonApiStructure\Tests\Concern;

use VGirol\JsonApiConstant\Members;
use VGirol\JsonApiStructure\Messages;
use VGirol\JsonApiStructure\Tests\TestCase;
use VGirol\JsonApiStructure\ValidateService;

class ValidateStructureTest extends TestCase
{
    /**
     * @test
     */
    public function topLevelLinksObjectIsValid()
    {
        $links = [
            Members::LINK_SELF => 'url'
        ];
        $withPagination = true;
        $strict = false;

        (new ValidateService())->validateTopLevelLinksMember($links, $withPagination, $strict);
        $this->succeed();
    }

    /**
     * @test
     * @dataProvider notValidTopLevelLinksObjectProvider
     */
    public function topLevelLinksObjectIsNotValid($json, $withPagination, $strict, $failureMessage, $code)
    {
        $this->setFailure($failureMessage, $code);
        (new ValidateService())->validateTopLevelLinksMember($json, $withPagination, $strict);
    }

    public function notValidTopLevelLinksObjectProvider()
    {
        return [
            'not allowed member' => [
                [
                    'anything' => 'not allowed'
                ],
                true,
                false,
                Messages::ONLY_ALLOWED_MEMBERS,
                403
            ]
        ];
    }
}
