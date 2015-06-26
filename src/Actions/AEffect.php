<?php

namespace ImagoOpus\Actions;

use ImagoOpus\Image;

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
