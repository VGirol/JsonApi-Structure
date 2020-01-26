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
     * Check if an array is an array of objects.
     *
     * @param array $json
     *
     * @return bool
     */
    public function isArrayOfObjects($json): bool
    {
        $this->isValidArgument(1, 'array', $json);

        if (\count($json) == 0) {
            return true;
        }

        return !$this->arrayIsAssociative($json);
    }

    /**
     * Validate that an array is an array of objects.
     *
     * @param array       $json
     * @param string|null $message     An optional message to explain why the test failed
     * @param mixed       $code
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function mustBeArrayOfObjects($json, ?string $message = '', $code = 403): void
    {
        if (!$this->isArrayOfObjects($json)) {
            $this->throw($message ?: Messages::MUST_BE_ARRAY_OF_OBJECTS, $code);
        }
    }

    /**
     * Check if an array is not an array of objects.
     *
     * @param array  $json
     *
     * @return bool
     */
    public function isNotArrayOfObjects($json): bool
    {
        $this->isValidArgument(1, 'array', $json);

        if (\count($json) == 0) {
            return true;
        }

        return $this->arrayIsAssociative($json);
    }

    /**
     * Validate that an array is not an array of objects.
     *
     * @param array  $json
     * @param string $message     An optional message to explain why the test failed
     * @param mixed  $code
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function mustNotBeArrayOfObjects($json, string $message = '', $code = 403): void
    {
        if (!$this->isNotArrayOfObjects($json)) {
            $this->throw($message ?: Messages::MUST_NOT_BE_ARRAY_OF_OBJECTS, $code);
        }
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
