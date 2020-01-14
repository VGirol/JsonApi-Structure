<?php

declare(strict_types=1);

namespace VGirol\JsonApiStructure\Tests\Concern;

use VGirol\JsonApiStructure\Exception\ValidationException;
use VGirol\JsonApiStructure\Messages;
use VGirol\JsonApiStructure\Tests\TestCase;
use VGirol\JsonApiStructure\ValidateService;

class ValidateMetaObjectTest extends TestCase
{
    /**
     * @test
     */
    public function metaObjectIsValid()
    {
        $json = [
            'key' => 'value',
            'another' => 'member'
        ];
        $strict = false;

        (new ValidateService)->validateMetaObject($json, $strict);
        $this->succeed();
    }

    /**
     * @test
     * @dataProvider notValidMetaObjectProvider
     */
    public function metaObjectIsNotValid($json, $strict, $failureMessage)
    {
        $this->setFailure(ValidationException::class, $failureMessage, 400);
        (new ValidateService)->validateMetaObject($json, $strict);
    }

    public function notValidMetaObjectProvider()
    {
        return [
            'not an associative array' => [
                [
                    [
                        'key' => 'failed'
                    ]
                ],
                false,
                Messages::META_OBJECT_IS_NOT_ARRAY
            ],
            'array of objects' => [
                [
                    [ 'key1' => 'element' ],
                    [ 'key2' => 'element' ]
                ],
                false,
                Messages::META_OBJECT_IS_NOT_ARRAY
            ],
            'key is not valid' => [
                [
                    'key+' => 'value'
                ],
                false,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ],
            'key is not safe' => [
                [
                    'not valid' => 'due to the blank character'
                ],
                true,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ]
        ];
    }
}
