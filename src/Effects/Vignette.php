<?php

namespace ImagoOpus\Effects;

use ImagoOpus\Image;
use ImagoOpus\Actions\AEffect;

class Vignette extends AEffect {
    public function run(Image $image) {
        $sigma = 10;
        $blur = $this->_fixRange($this->options['blur']?:10, 0, 255);
        $x = $this->_fixRange($this->options['x']?:10, 0, 1000);
        $y = $this->_fixRange($this->options['y']?:10, 0, 1000);

        $image->setIteratorIndex(0);
        do {
            $image->vignetteImage($blur, $sigma, $x, $y);
        } while($image->getNext());

        return $image;
    }
}