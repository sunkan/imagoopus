<?php

namespace ImagoOpus\Effects;

use ImagoOpus\Actions\AEffect;

class ModulateImage extends AEffect {
    protected function modulateImage($image, $brightness=0, $saturation=0, $hue=0) {
        $brightness = $this->_fixRange($brightness, -100, 100)+100;
        $saturation = $this->_fixRange($saturation, -100, 100)+100;
        $hue = $this->_fixRange($hue, -100, 100)+100;
        $image->setIteratorIndex(0);
        do {
            $image->modulateImage($brightness, $saturation, $hue);
        } while ($image->getNext());

        return $image;
    }
}

