<?php

namespace ImagoOpus\Actions;

abstract class AEffect extends AAction
{
    protected function _fixRange($value, $min, $max)
    {
        if ($value > $max) {
            return $max;
        } else if ($value < $min) {
            return -$min;
        }
        return $value;
    }
}
