<?php

namespace ImagoOpus\Effects;

use ImagoOpus\Image;
use ImagoOpus\Actions\AEffect;

class Negate extends AEffect {
    public function run(Image $image) {
        $image->setIteratorIndex(0);
        do {
            $image->negateImage(false);
        } while($image->nextImage());

        return $image;
    }
}
