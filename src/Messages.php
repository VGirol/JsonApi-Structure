<?php

declare(strict_types=1);

namespace VGirol\JsonApiStructure;

/**
 * All the messages
 */
abstract class Messages
{
    const ATTRIBUTES_OBJECT_IS_NOT_ARRAY =
    'An attributes object MUST be an array or an arrayable object with a "toArray" method.';
    const ERROR_CODE_IS_NOT_STRING =
    'The value of the "code" member MUST be a string.';
    const ERROR_DETAILS_IS_NOT_STRING =
    'The value of the "details" member MUST be a string.';
    const ERROR_OBJECT_NOT_ARRAY =
    'An error object MUST be an array.';
    const ERROR_OBJECT_NOT_EMPTY =
    'An error object MUST NOT be empty.';
    const ERROR_SOURCE_OBJECT_NOT_ARRAY =
    'An error source object MUST be an array.';
    const ERROR_SOURCE_PARAMETER_IS_NOT_STRING =
    'The value of the "parameter" member MUST be a string.';
    const ERROR_SOURCE_POINTER_IS_NOT_STRING =
    'The value of the "pointer" member MUST be a string.';
    const ERROR_SOURCE_POINTER_START =
    'The value of the "pointer" member MUST start with a slash (/).';
    const ERROR_STATUS_IS_NOT_STRING =
    'The value of the "status" member MUST be a string.';
    const ERROR_TITLE_IS_NOT_STRING =
    'The value of the "title" member MUST be a string.';
    const ERRORS_OBJECT_NOT_ARRAY =
    'Top-level "errors" member MUST be an array of error objects.';
    const FIELDS_HAVE_SAME_NAME =
    'A resource CAN NOT have an attribute and relationship with the same name.';
    const FIELDS_NAME_NOT_ALLOWED =
    'A resource CAN NOT have an attribute or relationship named "type" or "id".';
    const HAS_MEMBER =
    'Failed asserting that a JSON object HAS the member "%s".';
    const HAS_ONLY_MEMBERS =
    'Failed asserting that a JSON object HAS ONLY the members "%s".';
    const OBJECT_NOT_ARRAY =
    'A resource linkage MUST be an array of resource objects or resource identifier objects.';
    const LINK_OBJECT_MISS_HREF_MEMBER =
    'A link object MUST contain an "href" member.';
    const LINKS_OBJECT_NOT_ARRAY =
    'A links object MUST be an array.';
    const MEMBER_NAME_HAVE_RESERVED_CHARACTERS =
    'Member names MUST contain only allowed characters.';
    const MEMBER_NAME_IS_NOT_STRING =
    'Each member key MUST be a string.';
    const MEMBER_NAME_IS_TOO_SHORT =
    'Member names MUST contain at least one character.';
    const MEMBER_NAME_NOT_ALLOWED =
    'Any object that constitutes or is contained in an attribute MUST NOT contain a "relationships"
        or "links" member.';
    const MEMBER_NAME_START_AND_END_WITH_ALLOWED_CHARACTERS =
    'Member names MUST start and end with a globally allowed character.';
    const META_OBJECT_IS_NOT_ARRAY =
    'A meta object MUST be an array.';
    const NOT_HAS_MEMBER =
    'Failed asserting that a JSON object NOT HAS the member "%s".';
    const ONLY_ALLOWED_MEMBERS =
    'Unless otherwise noted, objects defined by this specification MUST NOT contain any additional members.';
    const PRIMARY_DATA_NOT_ARRAY =
    'Primary data MUST be an array or an arrayable object with a "toArray" method.';
    const PRIMARY_DATA_SAME_TYPE =
    'All elements of resource collection MUST be of same type (resource object or resource identifier object).';
    const RESOURCE_COLLECTION_NOT_ARRAY =
    'Resource collection MUST be represented as an empty array or an array of resource objects.';
    const RESOURCE_ID_MEMBER_IS_ABSENT =
    'A resource object MUST contain the "id" top-level members.';
    const RESOURCE_ID_MEMBER_IS_EMPTY =
    'The value of the "id" member CAN NOT be empty.';
    const RESOURCE_ID_MEMBER_IS_NOT_STRING =
    'The value of the "id" member MUST be a string.';
    const RESOURCE_IDENTIFIER_IS_NOT_ARRAY =
    'A resource identifier object MUST be an array.';
    const RESOURCE_IS_NOT_ARRAY =
    'A resource object MUST be an array.';
    const RESOURCE_LINKAGE_NOT_ARRAY =
    'Resource linkage MUST be represented as null, a single resource identifier object,
        an empty array or an array of resource identifier objects.';
    const RESOURCE_TYPE_MEMBER_IS_ABSENT =
    'A resource object MUST contain the "type" top-level members.';
    const RESOURCE_TYPE_MEMBER_IS_EMPTY =
    'The value of the "type" member CAN NOT be empty.';
    const RESOURCE_TYPE_MEMBER_IS_NOT_STRING =
    'The value of the "type" member MUST be a string.';
    const TEST_FAILED =
    'Failed asserting that test has failed.';
    const TOP_LEVEL_DATA_AND_ERROR =
    'The members "data" and "errors" MUST NOT coexist in the same JSON document.';
    const TOP_LEVEL_DATA_AND_INCLUDED =
    'If a document does not contain a top-level "data" member, the "included" member MUST NOT be present either.';
    const TOP_LEVEL_MEMBERS =
    'A JSON document MUST contain at least one of the following top-level members: "%s".';
    const COMPOUND_DOCUMENT_ONLY_ONE_RESOURCE =
    'A compound document MUST NOT include more than one resource object for each "type" and "id" pair.';
    const CONTAINS_AT_LEAST_ONE =
    'Must contain at least one element of "%s"';
    const JSONAPI_VERSION_IS_NOT_STRING =
    'If present, the value of the version member MUST be a string.';
    const LINK_OBJECT_IS_NOT_ARRAY =
    'A link MUST be represented as either a null value, a string or an object.';
    const MUST_NOT_BE_ARRAY_OF_OBJECTS =
    'Failed asserting that an array is not an array of objects.';
    const MUST_BE_ARRAY_OF_OBJECTS =
    'Failed asserting that an array is an array of objects.';
    const INCLUDED_RESOURCE_NOT_LINKED =
    'An included resource MUST correspond to an existing resource linkage.';
    const ERRORS_OBJECT_CONTAINS_NOT_ENOUGH_ERRORS =
    'Errors array must be greater or equal than the expected errors array.';
    const ERRORS_OBJECT_DOES_NOT_CONTAIN_EXPECTED_ERROR =
    'The "errors" member does not contain the expected error %s.';
    const RESOURCE_COLLECTION_HAVE_NOT_SAME_LENGTH =
    'Failed asserting that the resource collection length (%u) is equal to %u.';
    const RESOURCE_IS_NOT_EQUAL =
    'Failed asserting that the resource %s is equal to %s.';
    const PAGINATION_LINKS_NOT_EQUAL =
    'Failed asserting that pagination links equal expected values.';
    const PAGINATION_META_NOT_EQUAL =
    'Failed asserting that pagination meta equal expected values.';
    const RESOURCE_LINKAGE_COLLECTION_MUST_BE_EMPTY =
    'Failed asserting that the resource linkage collection is empty.';
    const RESOURCE_LINKAGE_COLLECTION_HAVE_NOT_SAME_LENGTH =
    'Failed asserting that the resource linkage collection length (%u) is equal to %u.';
    const RESOURCE_IDENTIFIER_IS_NOT_EQUAL =
    'Failed asserting that the resource identifier %s is equal to %s.';
    const RESOURCE_LINKAGE_MUST_BE_NULL =
    'Failed asserting that the resource linkage %s is null.';
    const RESOURCE_LINKAGE_MUST_NOT_BE_NULL =
    'Failed asserting that the resource linkage is not null.';
    const LINKS_OBJECT_HAVE_NOT_SAME_LENGTH =
    'Failed asserting that the links collection length (%u) is equal to %u.';
    const JSONAPI_OBJECT_NOT_EQUAL =
    'Failed asserting that the "jsonapi" object %s is equal to %s.';
}
