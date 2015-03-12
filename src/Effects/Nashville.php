<?php

namespace ImagoOpus\Effects;

use ImagoOpus\Image;
use ImagoOpus\Actions\AEffect;

class Nashville extends AEffect {
    public function run(Image $image) {

        $image->baseCurve('green', 36, true)
              ->baseCurve('blue', 122, true);

        $image->levels(0, 1.46, 240);
        // TODO: Add 10,45 brightness & contrast
        $image->baseCurve('green', 16)->
              ->baseCurve('blue', 77);
        // TODO: Add -6,16 brightness & contrast
        $image->baseCurve('blue', 16);
        $image->compositeColor('#f6d8ac', 'multiply');

        return $image;
    }
}
