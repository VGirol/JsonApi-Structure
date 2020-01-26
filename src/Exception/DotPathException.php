<?php

namespace VGirol\JsonApiStructure\Exception;

use Throwable;

/**
 * Exception for dot notation
 */
class DotPathException extends \Exception
{
    public const DOT_PATH_ERROR = 'Path "%s" is not valid : segment "%s" does not exists.';

    /**
     * Class constructor
     *
     * @param string $path
     * @param string $segment
     * @param integer $code
     * @param Throwable|null $previous
     *
     * @return void
     */
    public function __construct(string $path, string $segment, $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(sprintf(self::DOT_PATH_ERROR, $path, $segment), $code, $previous);
    }
}
