<?php

declare(strict_types=1);

namespace VGirol\JsonApiStructure;

/**
 * This trait add the ability to deal with flags using bitwise flag
 */
trait HaveBitwiseFlag
{
    /**
     * Undocumented variable
     *
     * @var int
     */
    private $flags;

    /**
     * Undocumented function
     *
     * @param integer $flag
     *
     * @return boolean
     */
    protected function isFlagSet(int $flag): bool
    {
        return (($this->flags & $flag) == $flag);
    }

    /**
     * Undocumented function
     *
     * @param integer $flag
     * @param boolean $value
     *
     * @return static
     */
    protected function setFlag(int $flag, bool $value)
    {
        if ($value) {
            $this->flags |= $flag;
        } else {
            $this->flags &= ~$flag;
        }

        return $this;
    }

    /**
     * Undocumented function
     *
     * @param integer $flag
     * @param array $flags
     *
     * @return static
     */
    protected function selectFlag(int $flag, array $flags)
    {
        $all = \array_reduce(
            $flags,
            /**
             * @param int $result
             * @param int $item
             *
             * @return int
             */
            function ($result, $item) {
                return $result | $item;
            },
            0
        );
        $this->setFlag($flag, true);
        $this->setFlag($all & ~$flag, false);

        return $this;
    }
}
