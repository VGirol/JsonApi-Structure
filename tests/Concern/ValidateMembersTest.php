<?php

namespace VGirol\JsonApiStructure\Tests\Concern;

use VGirol\JsonApiConstant\Members;
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
     * @dataProvider hasMemberInvalidArgumentProvider
     */
    public function hasMemberInvalidArgument($expected, $json, $arg, $type, $value)
    {
        $this->setInvalidArgumentException($arg, $type, $value);
        (new ValidateService())->hasMember($expected, $json);
    }

    /**
     */
    public function hasMemberInvalidArgumentProvider()
    {
        return [
            '$expected is not a string' => [
                666,
                [
                    'anything' => 'else'
                ],
                1,
                'string',
                666
            ],
            '$json is not an array' => [
                'anything',
                'invalid',
                2,
                'array',
                'invalid'
            ]
        ];
    }

    /**
     * @test
     */
    public function hasMemberFailed()
    {
        $expected = 'member';
        $json = [
            'anything' => 'else'
        ];
        $failureMessage = sprintf(Messages::HAS_MEMBER, 'member');
        $code = 403;

        $this->setFailure($failureMessage, $code);
        (new ValidateService())->hasMember($expected, $json);
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
     * @dataProvider hasMembersInvalidArgumentProvider
     */
    public function hasMembersInvalidArgument($expected, $json, $arg, $type, $value)
    {
        $this->setInvalidArgumentException($arg, $type, $value);
        (new ValidateService())->hasMembers($expected, $json);
    }

    public function hasMembersInvalidArgumentProvider()
    {
        return [
            '$expected is not an array' => [
                'invalid',
                [
                    'anything' => 'else'
                ],
                1,
                'array',
                'invalid'
            ],
            '$json is not an array' => [
                [
                    'anything'
                ],
                'invalid',
                2,
                'array',
                'invalid'
            ]
        ];
    }

    /**
     * @test
     */
    public function hasMembersFailed()
    {
        $expected = [
            'anything'
        ];
        $json = [
            'member1',
            'member2'
        ];
        $failureMessage = sprintf(Messages::HAS_MEMBER, 'anything');
        $code = 403;

        $this->setFailure($failureMessage, $code);
        (new ValidateService())->hasMembers($expected, $json);
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
     * @dataProvider hasOnlyMembersInvalidArgumentProvider
     */
    public function hasOnlyMembersInvalidArgument($expected, $json, $arg, $type, $value)
    {
        $this->setInvalidArgumentException($arg, $type, $value);
        (new ValidateService())->hasOnlyMembers($expected, $json);
    }

    public function hasOnlyMembersInvalidArgumentProvider()
    {
        $expected = 666;
        $json = 'invalid';

        return [
            '$expected is not an array' => [
                $expected,
                [
                    'anything' => 'else'
                ],
                1,
                'array',
                $expected
            ],
            '$json is not an array' => [
                [
                    'anything'
                ],
                $json,
                2,
                'array',
                $json
            ]
        ];
    }

    /**
     * @test
     */
    public function hasOnlyMembersFailed()
    {
        $expected = [
            'member1',
            'member2'
        ];
        $json = [
            'member1' => 'value1',
            'member2' => 'value2',
            'anything' => 'error'
        ];
        $failureMessage = sprintf(Messages::HAS_ONLY_MEMBERS, implode(', ', $expected));
        $code = 403;

        $this->setFailure($failureMessage, $code);
        (new ValidateService())->hasOnlyMembers($expected, $json);
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
     * @dataProvider notHasMemberInvalidArgumentProvider
     */
    public function notHasMemberInvalidArgument($expected, $json, $arg, $type, $value)
    {
        $this->setInvalidArgumentException($arg, $type, $value);
        (new ValidateService())->notHasMember($expected, $json);
    }

    public function notHasMemberInvalidArgumentProvider()
    {
        $expected = 666;
        $json = 'invalid';

        return [
            '$expected is not a string' => [
                $expected,
                [
                    'anything' => 'else'
                ],
                1,
                'string',
                $expected
            ],
            '$json is not an array' => [
                'anything',
                $json,
                2,
                'array',
                $json
            ]
        ];
    }

    /**
     * @test
     */
    public function notHasMemberFailed()
    {
        $expected = 'anything';
        $json = [
            'member' => 'value',
            'anything' => 'error'
        ];
        $failureMessage = sprintf(Messages::NOT_HAS_MEMBER, 'anything');
        $code = 403;

        $this->setFailure($failureMessage, $code);
        (new ValidateService())->notHasMember($expected, $json);
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
     * @dataProvider notHasMembersInvalidArgumentProvider
     */
    public function notHasMembersInvalidArgument($expected, $json, $arg, $type, $value)
    {
        $this->setInvalidArgumentException($arg, $type, $value);
        (new ValidateService())->notHasMembers($expected, $json);
    }

    public function notHasMembersInvalidArgumentProvider()
    {
        $expected = 666;
        $json = 'something';

        return [
            '$expected is not an array' => [
                $expected,
                [
                    'anything' => 'else'
                ],
                1,
                'array',
                $expected
            ],
            '$json is not an array' => [
                [
                    'anything'
                ],
                $json,
                2,
                'array',
                $json
            ]
        ];
    }

    /**
     * @test
     */
    public function notHasMembersFailed()
    {
        $expected = [
            'anything',
            'something'
        ];
        $json = [
            'member' => 'value',
            'anything' => 'error'
        ];
        $failureMessage = sprintf(Messages::NOT_HAS_MEMBER, 'anything');
        $code = 403;

        $this->setFailure($failureMessage, $code);
        (new ValidateService())->notHasMembers($expected, $json);
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

        $this->setFailure(null, null);
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
