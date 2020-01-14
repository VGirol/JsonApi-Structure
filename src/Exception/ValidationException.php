<?php

namespace VGirol\JsonApiStructure\Exception;

class ValidationException extends \Exception
{
    /**
     * Get the recommended HTTP status code to be used for the response.
     *
     * @return int
     */
    public function errorStatus()
    {
        return $this->getCode();
    }
}
