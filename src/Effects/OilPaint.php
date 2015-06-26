<?php

namespace ImagoOpus\Effects;

use ImagoOpus\Image;
use ImagoOpus\Actions\AEffect;

class OilPaint extends AEffect
{
    public function run(Image $image)
    {
        $radius = $this->_fixRange($this->options['stroke']?:2, 0, 10);
        $image->setIteratorIndex(0);
        do {
            $image->oilPaintImage($radius);
        } while ($image->getNext());

        return $image;
    }
}
