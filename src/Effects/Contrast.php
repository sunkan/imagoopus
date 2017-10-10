<?php

namespace ImagoOpus\Effects;

use ImagoOpus\Image;
use ImagoOpus\Actions\AEffect;

class Contrast extends AEffect
{
    public function run(Image $image)
    {
        $contrast = $this->_fixRange($this->options['contrast'], -100, 100);
        
        $image->setIteratorIndex(0);
        if (method_exists('Imagick', 'brightnessContrastImage')) {
            do {
                $image->brightnessContrastImage(0, $contrast);
            } while ($image->nextImage());
        } else {
            do {
                $image->sigmoidalContrastImage($contrast > 0 ? true : false, abs($contrast), 0.5);
            } while ($image->nextImage());
        }

        return $image;
    }
}
