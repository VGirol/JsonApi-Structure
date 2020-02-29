<?php

namespace VGirol\JsonApiStructure\Tests\Concern;

use VGirol\JsonApiConstant\Members;
use VGirol\JsonApiStructure\Messages;
use VGirol\JsonApiStructure\Tests\TestCase;
use VGirol\JsonApiStructure\ValidateService;

class ValidateIncludedTest extends TestCase
{
    /**
     * @test
     */
    public function compoundDocumentIsValid()
    {
        $json = [
            Members::DATA => [
                [
                    Members::TYPE => 'articles',
                    Members::ID => '1',
                    Members::ATTRIBUTES => [
                        'attr' => 'test'
                    ],
                    Members::RELATIONSHIPS => [
                        'anonymous' => [
                            Members::META => [
                                'key' => 'value'
                            ]
                        ],
                        'test' => [
                            Members::DATA => [
                                Members::TYPE => 'something',
                                Members::ID => '10'
                            ]
                        ]
                    ]
                ],
                [
                    Members::TYPE => 'articles',
                    Members::ID => '2',
                    Members::ATTRIBUTES => [
                        'attr' => 'another'
                    ]
                ]
            ],
            Members::INCLUDED => [
                [
                    Members::TYPE => 'something',
                    Members::ID => '10',
                    Members::ATTRIBUTES => [
                        'attr' => 'test'
                    ],
                    Members::RELATIONSHIPS => [
                        'anonymous' => [
                            Members::DATA => [
                                Members::TYPE => 'second',
                                Members::ID => '12'
                            ]
                        ]
                    ]
                ],
                [
                    Members::TYPE => 'second',
                    Members::ID => '12',
                    Members::ATTRIBUTES => [
                        'attr' => 'another test'
                    ]
                ]
            ]
        ];
        $strict = false;

        (new ValidateService())->validateIncludedCollection($json[Members::INCLUDED], $json[Members::DATA], $strict);
        $this->succeed();
    }

    /**
     * @test
     * @dataProvider notValidIncludedProvider
     */
    public function compoundDocumentIsNotValid($json, $strict, $failureMessage, $code)
    {
        $this->setValidationFailure($failureMessage, $code);
        (new ValidateService())->validateIncludedCollection($json[Members::INCLUDED], $json[Members::DATA], $strict);
    }

    public function notValidIncludedProvider()
    {
        return [
            'included member is not a resource collection' => [
                [
                    Members::DATA => [],
                    Members::INCLUDED => [
                        Members::ID => '1',
                        Members::TYPE => 'test'
                    ]
                ],
                false,
                Messages::MUST_BE_ARRAY_OF_OBJECTS,
                403
            ],
            'one included resource is not identified by a resource identifier object' => [
                [
                    Members::DATA => [
                        Members::TYPE => 'articles',
                        Members::ID => '1',
                        Members::RELATIONSHIPS => [
                            'anonymous' => [
                                Members::DATA => [
                                    Members::TYPE => 'something',
                                    Members::ID => '10'
                                ]
                            ]
                        ]
                    ],
                    Members::INCLUDED => [
                        [
                            Members::TYPE => 'something',
                            Members::ID => '10',
                            Members::ATTRIBUTES => [
                                'attr' => 'test'
                            ]
                        ],
                        [
                            Members::TYPE => 'something',
                            Members::ID => '12',
                            Members::ATTRIBUTES => [
                                'attr' => 'another'
                            ]
                        ]
                    ]
                ],
                false,
                Messages::INCLUDED_RESOURCE_NOT_LINKED,
                403
            ],
            'a resource is included twice' => [
                [
                    Members::DATA => [
                        Members::TYPE => 'articles',
                        Members::ID => '1',
                        Members::RELATIONSHIPS => [
                            'anonymous' => [
                                Members::DATA => [
                                    Members::TYPE => 'something',
                                    Members::ID => '10'
                                ]
                            ]
                        ]
                    ],
                    Members::INCLUDED => [
                        [
                            Members::TYPE => 'something',
                            Members::ID => '10',
                            Members::ATTRIBUTES => [
                                'attr' => 'test'
                            ]
                        ],
                        [
                            Members::TYPE => 'something',
                            Members::ID => '10',
                            Members::ATTRIBUTES => [
                                'attr' => 'test'
                            ]
                        ]
                    ]
                ],
                false,
                Messages::DOCUMENT_NO_DUPLICATE_RESOURCE,
                403
            ],
            'an included resource is not valid' => [
                [
                    Members::DATA => [
                        Members::TYPE => 'articles',
                        Members::ID => '1',
                        Members::RELATIONSHIPS => [
                            'anonymous' => [
                                Members::DATA => [
                                    Members::TYPE => 'something',
                                    Members::ID => '10'
                                ]
                            ]
                        ]
                    ],
                    Members::INCLUDED => [
                        [
                            Members::TYPE => 'something',
                            Members::ID => '10'
                        ]
                    ]
                ],
                false,
                sprintf(
                    Messages::CONTAINS_AT_LEAST_ONE,
                    implode(', ', [Members::ATTRIBUTES, Members::RELATIONSHIPS, Members::LINKS, Members::META])
                ),
                403
            ]
        ];
    }
}
