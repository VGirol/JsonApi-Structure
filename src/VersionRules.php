<?php

declare(strict_types=1);

namespace VGirol\JsonApiStructure;

use VGirol\JsonApiConstant\Members;
use VGirol\JsonApiConstant\Versions;

return [
    Versions::VERSION_1_0 => [
        'Document' => [
            'AtLeast' => [
                Members::DATA,
                Members::ERRORS,
                Members::META
            ],
            'Allowed' => [
                Members::DATA,
                Members::ERRORS,
                Members::META,
                Members::JSONAPI,
                Members::LINKS,
                Members::INCLUDED
            ],
            'LinksObject' => [
                'Allowed' => [
                    Members::LINK_SELF,
                    Members::LINK_RELATED
                ]
            ]
        ],
        'JsonapiObject' => [
            'Allowed' => [
                Members::JSONAPI_VERSION,
                Members::META
            ]
        ],
        'RelationshipObject' => [
            'AtLeast' => [
                Members::LINKS,
                Members::DATA,
                Members::META
            ]
        ],
        'MemberName' => [
            'Forbidden' => [
                Members::RELATIONSHIPS,
                Members::LINKS
            ]
        ],
        'LinksObject' => [
            'Pagination' => [
                Members::LINK_PAGINATION_FIRST,
                Members::LINK_PAGINATION_LAST,
                Members::LINK_PAGINATION_NEXT,
                Members::LINK_PAGINATION_PREV
            ]
        ],
        'ResourceIdentifierObject' => [
            'Allowed' => [
                Members::ID,
                Members::TYPE,
                Members::META
            ]
        ],
        'ResourceObject' => [
            'AtLeast' => [
                Members::ATTRIBUTES,
                Members::RELATIONSHIPS,
                Members::LINKS,
                Members::META
            ],
            'Allowed' => [
                Members::ID,
                Members::TYPE,
                Members::META,
                Members::ATTRIBUTES,
                Members::LINKS,
                Members::RELATIONSHIPS
            ],
            'LinksObject' => [
                'Allowed' => [
                    Members::LINK_SELF
                ]
            ],
            'FieldName' => [
                'Forbidden' => [
                    Members::TYPE,
                    Members::ID
                ]
            ]
        ],
        'ErrorObject' => [
            'Allowed' => [
                Members::ID,
                Members::LINKS,
                Members::ERROR_STATUS,
                Members::ERROR_CODE,
                Members::ERROR_TITLE,
                Members::ERROR_DETAILS,
                Members::ERROR_SOURCE,
                Members::META
            ],
            'LinksObject' => [
                'Allowed' => [
                    Members::LINK_ABOUT
                ]
            ]
        ],
        'LinkObject' => [
            'Allowed' => [
                Members::LINK_HREF,
                Members::META
            ]
        ]
    ],
    Versions::VERSION_1_1 => [
    ]
];
