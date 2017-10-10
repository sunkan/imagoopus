<?php

namespace ImagoOpus\Effects;

use ImagoOpus\Image;
use ImagoOpus\Actions\AEffect;

class Blur extends AEffect
{
    public function run(Image $image)
    {
        $radius = $this->_fixRange($this->options['radius'], 0, 100) / 10;
        
        $image->setIteratorIndex(0);
        do {
            $image->blurImage($radius, 100);
        } while ($image->nextImage());

        return $image;
    }
}
