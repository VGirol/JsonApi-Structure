<?php

declare(strict_types=1);

namespace VGirol\JsonApiStructure\Tests\Concern;

use TypeError;
use VGirol\JsonApiConstant\Members;
use VGirol\JsonApiStructure\Exception\ValidationException;
use VGirol\JsonApiStructure\Messages;
use VGirol\JsonApiStructure\Tests\TestCase;
use VGirol\JsonApiStructure\ValidateService;

class ValidateMembersTest extends TestCase
{
    /**
     * @test
     */
    public function hasMember()
    {
        $json = [
            Members::META => ['key' => 'value'],
            'anything' => 'else'
        ];
        $expected = Members::META;

        (new ValidateService())->hasMember($expected, $json);
        $this->succeed();
    }

    /**
     * @test
     * @dataProvider hasMemberFailedProvider
     */
    public function hasMemberFailed($expected, $json, $exceptionClass, $failureMessage, $code)
    {
        $this->setFailure($exceptionClass, $failureMessage, $code);
        (new ValidateService())->hasMember($expected, $json);
    }

    /**
     */
    public function hasMemberFailedProvider()
    {
        return [
            '$expected is not a string' => [
                666,
                [
                    'anything' => 'else'
                ],
                TypeError::class,
                null,
                null
            ],
            '$json is not an array' => [
                'anything',
                'invalid',
                TypeError::class,
                null,
                null
            ],
            'member is not present' => [
                'member',
                [
                    'anything' => 'else'
                ],
                ValidationException::class,
                sprintf(Messages::HAS_MEMBER, 'member'),
                400
            ],
        ];
    }

    /**
     * @test
     */
    public function hasMembers()
    {
        $json = [
            Members::META => ['key' => 'value'],
            Members::JSONAPI => [
                Members::JSONAPI_VERSION => 'v1.0'
            ],
            'anything' => 'else'
        ];
        $expected = [Members::META, Members::JSONAPI];

        (new ValidateService())->hasMembers($expected, $json);
        $this->succeed();
    }

    /**
     * @test
     * @dataProvider hasMembersFailedProvider
     */
    public function hasMembersFailed($expected, $json, $exceptionClass, $failureMessage, $code)
    {
        $this->setFailure($exceptionClass, $failureMessage, $code);
        (new ValidateService())->hasMembers($expected, $json);
    }

    public function hasMembersFailedProvider()
    {
        return [
            '$expected is not an array' => [
                'invalid',
                [
                    'anything' => 'else'
                ],
                TypeError::class,
                null,
                null
            ],
            '$json is not an array' => [
                [
                    'anything'
                ],
                'invalid',
                TypeError::class,
                null,
                null
            ],
            'expected members are not present' => [
                [
                    'anything'
                ],
                [
                    'member1',
                    'member2'
                ],
                ValidationException::class,
                sprintf(Messages::HAS_MEMBER, 'anything'),
                400
            ]
        ];
    }

    /**
     * @test
     */
    public function hasOnlyMembers()
    {
        $json = [
            Members::META => ['key' => 'value'],
            Members::JSONAPI => [
                Members::JSONAPI_VERSION => 'v1.0'
            ]
        ];
        $expected = [Members::META, Members::JSONAPI];

        (new ValidateService())->hasOnlyMembers($expected, $json);
        $this->succeed();
    }

    /**
     * @test
     * @dataProvider hasOnlyMembersFailedProvider
     */
    public function hasOnlyMembersFailed($expected, $json, $exceptionClass, $failureMessage, $code)
    {
        $this->setFailure($exceptionClass, $failureMessage, $code);
        (new ValidateService())->hasOnlyMembers($expected, $json);
    }

    public function hasOnlyMembersFailedProvider()
    {
        return [
            '$expected is not an array' => [
                666,
                [
                    'anything' => 'else'
                ],
                TypeError::class,
                null,
                null
            ],
            '$json is not an array' => [
                [
                    'anything'
                ],
                'invalid',
                TypeError::class,
                null,
                null
            ],
            'not has only mermbers' => [
                [
                    'member1',
                    'member2'
                ],
                [
                    'member1' => 'value1',
                    'member2' => 'value2',
                    'anything' => 'error'
                ],
                ValidationException::class,
                sprintf(Messages::HAS_ONLY_MEMBERS, implode(', ', ['member1', 'member2'])),
                400
            ]
        ];
    }

    /**
     * @test
     */
    public function notHasMember()
    {
        $json = [
            Members::META => ['key' => 'value'],
            Members::JSONAPI => [
                Members::JSONAPI_VERSION => 'v1.0'
            ]
        ];
        $expected = 'test';

        (new ValidateService())->notHasMember($expected, $json);
        $this->succeed();
    }

    /**
     * @test
     * @dataProvider notHasMemberFailedProvider
     */
    public function notHasMemberFailed($expected, $json, $exceptionClass, $failureMessage, $code)
    {
        $this->setFailure($exceptionClass, $failureMessage, $code);
        (new ValidateService())->notHasMember($expected, $json);
    }

    public function notHasMemberFailedProvider()
    {
        return [
            '$expected is not a string' => [
                666,
                [
                    'anything' => 'else'
                ],
                TypeError::class,
                null,
                null
            ],
            '$json is not an array' => [
                'anything',
                'invalid',
                TypeError::class,
                null,
                null
            ],
            'member already present' => [
                'anything',
                [
                    'member' => 'value',
                    'anything' => 'error'
                ],
                ValidationException::class,
                sprintf(Messages::NOT_HAS_MEMBER, 'anything'),
                400
            ]
        ];
    }

    /**
     * @test
     */
    public function notHasMembers()
    {
        $json = [
            Members::META => ['key' => 'value'],
            Members::JSONAPI => [
                Members::JSONAPI_VERSION => 'v1.0'
            ],
        ];
        $expected = [
            'test',
            'something'
        ];

        (new ValidateService())->notHasMembers($expected, $json);
        $this->succeed();
    }

    /**
     * @test
     * @dataProvider notHasMembersFailedProvider
     */
    public function notHasMembersFailed($expected, $json, $exceptionClass, $failureMessage, $code)
    {
        $this->setFailure($exceptionClass, $failureMessage, $code);
        (new ValidateService())->notHasMembers($expected, $json);
    }

    public function notHasMembersFailedProvider()
    {
        return [
            '$expected is not an array' => [
                666,
                [
                    'anything' => 'else'
                ],
                TypeError::class,
                null,
                null
            ],
            'members already present' => [
                [
                    'anything',
                    'something'
                ],
                [
                    'member' => 'value',
                    'anything' => 'error'
                ],
                ValidationException::class,
                sprintf(Messages::NOT_HAS_MEMBER, 'anything'),
                400
            ]
        ];
    }

    /**
     * @test
     * @dataProvider hasSpecificMemberProvider
     */
    public function hasSpecificMember($fn, $json)
    {
        (new ValidateService())->{$fn}($json);
        $this->succeed();
    }

    public function hasSpecificMemberProvider()
    {
        $json = [
            Members::DATA => [
                Members::ATTRIBUTES => [
                    'attr' => 'value'
                ],
                Members::RELATIONSHIPS => []
            ],
            Members::INCLUDED => [],
            Members::META => [
                'key' => 'value'
            ],
            Members::JSONAPI => [
                Members::JSONAPI_VERSION => 'v1.0'
            ],
            Members::LINKS => [
                Members::LINK_SELF => 'url'
            ],
            Members::ERRORS => [],
        ];

        return [
            'has data' => [
                'hasData',
                $json
            ],
            'has attributes' => [
                'hasAttributes',
                $json[Members::DATA]
            ],
            'has links' => [
                'hasLinks',
                $json
            ],
            'has meta' => [
                'hasMeta',
                $json
            ],
            'has included' => [
                'hasIncluded',
                $json
            ],
            'has relationships' => [
                'hasRelationships',
                $json[Members::DATA]
            ],
            'has errors' => [
                'hasErrors',
                $json
            ],
            'has jsonapi' => [
                'hasJsonapi',
                $json
            ],
        ];
    }

    /**
     * @test
     * @dataProvider hasSpecificMemberFailedProvider
     */
    public function hasSpecificMemberFailed($fn)
    {
        $json = [
            'member' => 'value'
        ];

        $this->setFailure(ValidationException::class, null, null);
        (new ValidateService())->{$fn}($json);
    }

    public function hasSpecificMemberFailedProvider()
    {
        return [
            'has data' => [
                'hasData'
            ],
            'has attributes' => [
                'hasAttributes'
            ],
            'has links' => [
                'hasLinks'
            ],
            'has meta' => [
                'hasMeta'
            ],
            'has included' => [
                'hasIncluded'
            ],
            'has relationships' => [
                'hasRelationships'
            ],
            'has errors' => [
                'hasErrors'
            ],
            'has jsonapi' => [
                'hasJsonapi'
            ],
        ];
    }
}
