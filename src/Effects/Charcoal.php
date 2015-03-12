<?php

namespace ImagoOpus\Effects;

use ImagoOpus\Image;
use ImagoOpus\Actions\AEffect;

class Charcoal extends AEffect {
    public function run(Image $image) {
        $radius = $this->_fixRange($this->options['radius']?:2, 0, 10);
        $image->setIteratorIndex(0);
        do {
            $image->charcoalImage($radius, 2);
        } while($image->nextImage());

        return $image;
    }
}
