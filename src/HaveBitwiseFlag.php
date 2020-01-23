<?php

declare(strict_types=1);

namespace VGirol\JsonApiStructure;

trait HaveBitwiseFlag
{
    private $flags;

    protected function isFlagSet(int $flag): bool
    {
        return (($this->flags & $flag) == $flag);
    }

    protected function setFlag(int $flag, bool $value)
    {
        if ($value) {
            $this->flags |= $flag;
        } else {
            $this->flags &= ~$flag;
        }

        return $this;
    }

    protected function selectFlag(int $flag, array $flags)
    {
        $all = \array_reduce(
            $flags,
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
