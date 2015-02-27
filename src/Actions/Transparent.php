<?php

namespace ImagoOpus\Actions;

use ImagoOpus\Image;

class Transparent extends AAction {

    public function run(Image $image) {
        $image->setImageFormat('png');
        $target = $image->getImagePixelColor(1, 1);
        $fuzz = 0.01;
        if ($target->getColorAsString()!='srgb(255,255,255)' && $target->getColorAsString()!='rgb(255,255,255)') {
            $colors = $image->getImageHistogram(2);
            $target = array_shift($colors);
            $fuzz = 0.08;
        }

        $image->transparentPaintImage($target, 0, $image->getQuantum()*$fuzz, false);

        return $image;
    }
}