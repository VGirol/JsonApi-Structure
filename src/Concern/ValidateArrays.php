<?php

declare(strict_types=1);

namespace VGirol\JsonApiStructure\Concern;

use VGirol\JsonApiStructure\Messages;

/**
 * Validations relating to the arrays
 */
trait ValidateArrays
{
    /**
     * Validate that an array is an array of objects.
     *
     * @param array  $json
     * @param string $message An optional message to explain why the test failed
     *
     * @return bool
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function isArrayOfObjects(array $json, string $message = '', $returnValue = false): bool
    {
        if (\count($json) == 0) {
            return true;
        }

        $result = !$this->arrayIsAssociative($json);

        if (!$result && !$returnValue) {
            $message = $message ?: Messages::MUST_BE_ARRAY_OF_OBJECTS;
            $this->throw($message, 400);
        }

        return $result;
    }

    /**
     * Validate that an array is not an array of objects.
     *
     * @param array  $json
     * @param string $message An optional message to explain why the test failed
     *
     * @return bool
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function isNotArrayOfObjects(array $json, string $message = '', $returnValue = false): bool
    {
        if (\count($json) == 0) {
            return true;
        }

        $result = $this->arrayIsAssociative($json);

        if (!$result && !$returnValue) {
            $message = $message ?: Messages::MUST_NOT_BE_ARRAY_OF_OBJECTS;
            $this->throw($message, 400);
        }

        return $result;
    }

    /**
     * Checks if the given array is an associative array.
     *
     * @param array $arr
     *
     * @return boolean
     */
    private function arrayIsAssociative(array $arr): bool
    {
        return (\array_keys($arr) !== \range(0, \count($arr) - 1));
    }
}
