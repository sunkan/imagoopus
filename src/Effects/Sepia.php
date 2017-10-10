<?php

namespace ImagoOpus\Effects;

use ImagoOpus\Image;
use ImagoOpus\Actions\AEffect;

class Sepia extends AEffect
{
    public function run(Image $image)
    {
        $sepia = $this->_fixRange($this->options['sepia']?:80, 0, $image->getQuantum());
        $image->setIteratorIndex(0);
        do {
            $image->sepiaToneImage($sepia);
        } while ($image->nextImage());

        return $image;
    }
}
