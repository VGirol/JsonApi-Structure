<?php

declare(strict_types=1);

namespace VGirol\JsonApiStructure\Constraint;

use VGirol\JsonApiStructure\Exception\ValidationException;

abstract class Constraint
{
    /**
     * Undocumented variable
     *
     * @var string
     */
    private $failureMessage;

    /**
     * Returns a string representation of the constraint.
     */
    abstract public function default(): string;

    /**
     * Returns a string representation of the constraint.
     */
    public function toString(): string
    {
        $failureMessage = $this->default();
        if ($this->failureMessage) {
            $failureMessage .= "\n" . $this->failureMessage;
        }

        return $failureMessage;
    }

    /**
     * Evaluates the constraint for parameter $other
     *
     * If $returnResult is set to false (the default), an exception is thrown
     * in case of a failure. true is returned otherwise.
     *
     * If $returnResult is true, the result of the evaluation is returned as
     * a boolean value instead: true in case of success, false in case of a
     * failure.
     *
     * @return bool
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function evaluate($inspected, string $description = '', bool $returnResult = false, $code = 403): bool
    {
        $success = $this->handle($inspected);

        if ($returnResult) {
            return $success;
        }

        if (!$success) {
            $this->fail($description, $code);
        }

        return true;
    }

    /**
     * Evaluates the constraint for parameter $inspected. Returns true if the constraint is met, false otherwise.
     *
     * @param mixed  $inspected value or object to evaluate
     *
     * @return boolean
     */
    abstract protected function handle($inspected): bool;

    /**
     * Undocumented function
     *
     * @param string $message
     *
     * @return void
     */
    protected function setFailureMessage(string $message): void
    {
        $this->failureMessage = $message;
    }

    /**
     * Undocumented function
     *
     * @param string $description
     * @param mixed  $code
     *
     * @return void
     */
    private function fail(string $description, $code)
    {
        $failureMessage = $this->toString();
        if ($description) {
            $failureMessage .= "\n" . $description;
        }

        throw new ValidationException($failureMessage, $code);
    }
}
