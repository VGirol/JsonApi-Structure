<?php

declare(strict_types=1);

namespace VGirol\JsonApiStructure;

/**
 * All the messages
 */
abstract class Messages
{
    const ATTRIBUTES_OBJECT_MUST_BE_ARRAY =
    'An attributes object MUST be an array or an arrayable object with a "toArray" method.';

    const DOCUMENT_TOP_LEVEL_MEMBERS =
    'A JSON document MUST contain at least one of the following top-level members: "%s".';
    const DOCUMENT_DOCUMENT_TOP_LEVEL_MEMBERS_DATA_AND_ERROR =
    'The members "data" and "errors" MUST NOT coexist in the same JSON document.';
    const DOCUMENT_DOCUMENT_TOP_LEVEL_MEMBERS_DATA_AND_INCLUDED =
    'If a document does not contain a top-level "data" member, the "included" member MUST NOT be present either.';
    const DOCUMENT_NO_DUPLICATE_RESOURCE =
    'A compound document MUST NOT include more than one resource object for each "type" and "id" pair.';
    const INCLUDED_RESOURCE_NOT_LINKED =
    'An included resource MUST correspond to an existing resource linkage.';
    const PRIMARY_DATA_SAME_TYPE =
    'All elements of resource collection MUST be of same type (resource object or resource identifier object).';

    const ERROR_OBJECT_CODE_MEMBER_MUST_BE_STRING =
    'The value of the "code" member MUST be a string.';
    const ERROR_OBJECT_DETAILS_MEMBER_MUST_BE_STRING =
    'The value of the "details" member MUST be a string.';
    const ERROR_OBJECT_MUST_BE_ARRAY =
    'An error object MUST be an array.';
    const ERROR_OBJECT_MUST_NOT_BE_EMPTY =
    'An error object MUST NOT be empty.';
    const ERROR_OBJECT_TITLE_MEMBER_MUST_BE_STRING =
    'The value of the "title" member MUST be a string.';
    const ERROR_OBJECT_STATUS_MEMBER_MUST_BE_STRING =
    'The value of the "status" member MUST be a string.';
    const ERROR_OBJECT_SOURCE_OBJECT_MUST_BE_ARRAY =
    'An error source object MUST be an array.';
    const ERROR_SOURCE_PARAMETER_IS_NOT_STRING =
    'The value of the "parameter" member MUST be a string.';
    const ERROR_SOURCE_POINTER_IS_NOT_STRING =
    'The value of the "pointer" member MUST be a string.';
    const ERROR_SOURCE_POINTER_START =
    'The value of the "pointer" member MUST start with a slash (/).';

    const ERRORS_OBJECT_MUST_BE_ARRAY =
    'Top-level "errors" member MUST be an array of error objects.';

    const JSONAPI_OBJECT_VERSION_MEMBER_MUST_BE_STRING =
    'If present, the value of the version member MUST be a string.';

    const LINK_OBJECT_MISS_HREF_MEMBER =
    'A link object MUST contain an "href" member.';
    const LINK_OBJECT_BAD_TYPE =
    'A link MUST be represented as either a null value, a string or an object.';

    const LINKS_OBJECT_NOT_ARRAY =
    'A links object MUST be an array.';

    const MEMBER_NAME_MUST_NOT_HAVE_RESERVED_CHARACTERS =
    'Member names MUST contain only allowed characters.';
    const MEMBER_NAME_MUST_BE_STRING =
    'Each member key MUST be a string.';
    const MEMBER_NAME_IS_TOO_SHORT =
    'Member names MUST contain at least one character.';
    const MEMBER_NAME_NOT_ALLOWED =
    'Any object that constitutes or is contained in an attribute MUST NOT contain a "relationships"
        or "links" member.';
    const MEMBER_NAME_MUST_START_AND_END_WITH_ALLOWED_CHARACTERS =
    'Member names MUST start and end with a globally allowed character.';
    const NOT_HAS_MEMBER =
    'Failed asserting that a JSON object NOT HAS the member "%s".';
    const HAS_MEMBER =
    'Failed asserting that a JSON object HAS the member "%s".';
    const HAS_ONLY_MEMBERS =
    'Failed asserting that a JSON object HAS ONLY the members "%s".';

    const META_OBJECT_MUST_BE_ARRAY =
    'A meta object MUST be an array.';

    const RESOURCE_ID_MEMBER_IS_ABSENT =
    'A resource object MUST contain the "id" top-level members.';
    const RESOURCE_ID_MEMBER_CAN_NOT_BE_EMPTY =
    'The value of the "id" member CAN NOT be empty.';
    const RESOURCE_ID_MEMBER_MUST_BE_STRING =
    'The value of the "id" member MUST be a string.';
    const RESOURCE_TYPE_MEMBER_IS_ABSENT =
    'A resource object MUST contain the "type" top-level members.';
    const RESOURCE_TYPE_MEMBER_CAN_NOT_BE_EMPTY =
    'The value of the "type" member CAN NOT be empty.';
    const RESOURCE_TYPE_MEMBER_MUST_BE_STRING =
    'The value of the "type" member MUST be a string.';
    const RESOURCE_FIELDS_CAN_NOT_HAVE_SAME_NAME =
    'A resource CAN NOT have an attribute and relationship with the same name.';
    const RESOURCE_FIELDS_NAME_NOT_ALLOWED =
    'A resource CAN NOT have an attribute or relationship named "type" or "id".';
    const RESOURCE_OBJECT_MUST_BE_ARRAY =
    'A resource object MUST be an array.';

    const RESOURCE_OBJECT_COLLECTION_MUST_BE_ARRAY =
    'Resource collection MUST be represented as an empty array or an array of resource objects.';

    const RESOURCE_IDENTIFIER_MUST_BE_ARRAY =
    'A resource identifier object MUST be an array.';

    const RESOURCE_LINKAGE_BAD_TYPE =
    'Resource linkage MUST be represented as null, a single resource identifier object,
        an empty array or an array of resource identifier objects.';

    const ONLY_ALLOWED_MEMBERS =
    'Unless otherwise noted, objects defined by this specification MUST NOT contain any additional members.';
    const CONTAINS_AT_LEAST_ONE =
    'Object does not contain at least one element of "%s".';
    const MEMBER_NAME_NOT_VALID =
    'Member name is not valid.';
    const MUST_NOT_BE_ARRAY_OF_OBJECTS =
    'Failed asserting that an array is not an array of objects.';
    const MUST_BE_ARRAY_OF_OBJECTS =
    'Failed asserting that an array is an array of objects.';

    public const REQUEST_ERROR_NO_DATA_MEMBER =
    'The request MUST include a top-level member named "data".';
    public const REQUEST_ERROR_DATA_MEMBER_NULL =
    'The request MUST include a top-level member named "data" which value MUST NOT be null.';
    public const REQUEST_ERROR_DATA_MEMBER_NOT_ARRAY =
    'The request MUST include a top-level member named "data" which value MUST NOT be a %s.';
    public const REQUEST_ERROR_DATA_MEMBER_NOT_SINGLE =
    'The request MUST include a top-level member named "data" which value MUST be a single object.';
    public const RELATIONSHIP_NO_DATA_MEMBER =
    'If a relationship is provided in the relationships member of the resource object, ' .
    'its value MUST be a relationship object with a "data" member. ';
    public const REQUEST_ERROR_DATA_MEMBER_NOT_COLLECTION =
    'The request MUST include a top-level member named "data" which value MUST be a collection of objects.';
}
