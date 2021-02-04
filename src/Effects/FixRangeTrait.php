<?php declare(strict_types=1);

namespace ImagoOpus\Effects;

trait FixRangeTrait
{
    private function fixRange(float $value, float $min, float $max): float
    {
        if ($value > $max) {
            return $max;
        }
        elseif ($value < $min) {
            return -$min;
        }
        return $value;
    }
}
