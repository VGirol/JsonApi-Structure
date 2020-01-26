<?php

declare(strict_types=1);

namespace VGirol\JsonApiStructure\Constraint;

use VGirol\JsonApiStructure\Exception\ValidationException;

/**
 * Abstract class for constraint
 */
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
     * @param mixed      $inspected
     * @param string     $description
     * @param string|int $code
     *
     * @return void
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
     */
    public function evaluate($inspected, string $description = '', $code = 403): void
    {
        $success = $this->handle($inspected);

        if (!$success) {
            $this->fail($description, $code);
        }
    }

    /**
     * Evaluates the constraint for parameter $inspected. Returns true if the constraint is met, false otherwise.
     *
     * @param mixed  $inspected value or object to evaluate
     *
     * @return boolean
     */
    abstract public function handle($inspected): bool;

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
     * @throws \VGirol\JsonApiStructure\Exception\ValidationException
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
