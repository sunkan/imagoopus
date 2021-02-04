<?php declare(strict_types=1);

namespace ImagoOpus\Effects;

use Imagick;
use ImagoOpus\Image;

class Nashville implements EffectInterface
{
    public function run(Image $image): Image
    {
        $image->baseCurve(Imagick::CHANNEL_GREEN, 36, true)
              ->baseCurve(Imagick::CHANNEL_BLUE, 122, true);

        $image->levels(0, 1.46, 240);
        // TODO: Add 10,45 brightness & contrast
        $image->baseCurve(Imagick::CHANNEL_GREEN, 16)
              ->baseCurve(Imagick::CHANNEL_BLUE, 77);
        // TODO: Add -6,16 brightness & contrast
        $image->baseCurve(Imagick::CHANNEL_BLUE, 16);
        $image->compositeColor('#f6d8ac', Imagick::COMPOSITE_MULTIPLY);

        return $image;
    }
}
