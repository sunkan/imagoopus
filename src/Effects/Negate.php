<?php declare(strict_types=1);

namespace ImagoOpus\Effects;

use ImagoOpus\Image;

class Negate implements EffectInterface
{
    public function run(Image $image): Image
    {
        $image->setIteratorIndex(0);
        do {
            $image->negateImage(false);
        }
        while ($image->nextImage());

        return $image;
    }
}
