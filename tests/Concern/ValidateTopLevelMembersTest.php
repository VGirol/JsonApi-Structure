<?php

namespace VGirol\JsonApiStructure\Tests\Concern;

use VGirol\JsonApiConstant\Members;
use VGirol\JsonApiStructure\Messages;
use VGirol\JsonApiStructure\Tests\TestCase;
use VGirol\JsonApiStructure\ValidateService;

class ValidateTopLevelMembersTest extends TestCase
{
    /**
     * @test
     */
    public function documentHasValidTopLevelMembers()
    {
        $json = [
            Members::LINKS => [
                Members::LINK_SELF => 'http://example.com/articles'
            ],
            Members::DATA => [
                [
                    Members::TYPE => 'articles',
                    Members::ID => '1',
                    Members::ATTRIBUTES => [
                        Members::ERROR_TITLE => 'First'
                    ]
                ],
                [
                    Members::TYPE => 'articles',
                    Members::ID => '2',
                    Members::ATTRIBUTES => [
                        Members::ERROR_TITLE => 'Second'
                    ]
                ]
            ]
        ];

        (new ValidateService())->validateTopLevelMembers($json);
        $this->succeed();
    }

    /**
     * @test
     * @dataProvider notValidTopLevelMembersProvider
     */
    public function documentHasNotValidTopLevelMembers($json, $failureMessage, $code)
    {
        $this->setFailure($failureMessage, $code);
        (new ValidateService())->validateTopLevelMembers($json);
    }

    public function notValidTopLevelMembersProvider()
    {
        return [
            'miss mandatory members' => [
                [
                    Members::LINKS => [
                        Members::LINK_SELF => 'http://example.com/articles'
                    ]
                ],
                sprintf(Messages::TOP_LEVEL_MEMBERS, implode('", "', [Members::DATA, Members::ERRORS, Members::META])),
                403
            ],
            'data and error incompatible' => [
                [
                    Members::ERRORS => [
                        [
                            Members::ERROR_CODE => 'E13'
                        ]
                    ],
                    Members::DATA => [
                        Members::TYPE => 'articles',
                        Members::ID => '1',
                        Members::ATTRIBUTES => [
                            Members::ERROR_TITLE => 'JSON:API paints my bikeshed!'
                        ]
                    ]
                ],
                Messages::TOP_LEVEL_DATA_AND_ERROR,
                403
            ],
            'only allowed members' => [
                [
                    Members::DATA => [
                        Members::TYPE => 'articles',
                        Members::ID => '1',
                        Members::ATTRIBUTES => [
                            Members::ERROR_TITLE => 'JSON:API paints my bikeshed!'
                        ]
                    ],
                    'anything' => 'not allowed'
                ],
                Messages::ONLY_ALLOWED_MEMBERS,
                403
            ],
            'no data but included' => [
                [
                    Members::INCLUDED => [
                        [
                            Members::TYPE => 'articles',
                            Members::ID => '1',
                            Members::ATTRIBUTES => [
                                Members::ERROR_TITLE => 'JSON:API paints my bikeshed!'
                            ]
                        ]
                    ],
                    Members::META => [
                        'anything' => 'ok'
                    ]
                ],
                Messages::TOP_LEVEL_DATA_AND_INCLUDED,
                403
            ]
        ];
    }
}
