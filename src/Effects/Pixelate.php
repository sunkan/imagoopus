<?php

namespace ImagoOpus\Effects;

use ImagoOpus\Image;
use ImagoOpus\Actions\AEffect;

class Sepia extends AEffect
{
    public function run(Image $image)
    {
        $pixelSize = $this->_fixRange($this->options['size']?:10, 3, 100);
        $dim = $image->getImageGeometry();
        $tmpWidth = $dim['width']/$pixelSize;
        $tmpHeight = $dim['height']/$pixelSize;

        $image->setIteratorIndex(0);
        do {
            $image->scaleImage($tmpWidth, $tmpHeight);
            $image->scaleImage($dim['width'], $dim['height']);
        } while ($image->getNext());

        return $image;
    }
}
