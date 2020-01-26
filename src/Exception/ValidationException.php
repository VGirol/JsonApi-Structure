<?php

namespace VGirol\JsonApiStructure\Exception;

/**
 * Exception for validation
 */
class ValidationException extends \Exception
{
    /**
     * Get the recommended HTTP status code to be used for the response.
     *
     * @return int
     */
    public function errorStatus(): int
    {
        return $this->getCode();
    }
}
