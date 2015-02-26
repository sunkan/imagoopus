<?php

namespace ImagoOpus\Actions;

use ImagoOpus\Image;

class Transparent extends AAction {

    public function run(Image $image) {
        $target = $image->getImagePixelColor(1, 1);
        $fuzz = 0.1;
        if ($target->getColorAsString()!='rgb(255,255,255)') {
            $colors = $image->getImageHistogram(2);
            $target = array_shift($colors);
            $fuzz = 0.8;
        }

        $image->transparentPaintImage($target, 0, $image->getQuantum()*$fuzz, true);

        return $image;
    }
}