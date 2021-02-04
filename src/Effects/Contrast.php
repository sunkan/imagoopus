<?php declare(strict_types=1);

namespace ImagoOpus\Effects;

use Imagick;
use ImagoOpus\Image;

class Contrast implements EffectInterface
{
    use FixRangeTrait;

    private float $contrast;

    public function __construct(float $contrast)
    {
        $this->contrast = $contrast;
    }

    public function run(Image $image): Image
    {
        $contrast = $this->fixRange($this->contrast, -100, 100);

        $image->setIteratorIndex(0);
        if (method_exists(Imagick::class, 'brightnessContrastImage')) {
            do {
                $image->brightnessContrastImage(0, $contrast);
            }
            while ($image->nextImage());
        }
        else {
            do {
                $image->sigmoidalContrastImage($contrast > 0, abs($contrast), 0.5);
            }
            while ($image->nextImage());
        }

        return $image;
    }
}
