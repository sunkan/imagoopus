<?php declare(strict_types=1);

namespace ImagoOpus\Effects;

use ImagoOpus\Image;

trait ModulateImageTrait
{
    use FixRangeTrait;

    private function modulateImage(Image $image, float $brightness = 0, float $saturation = 0, float $hue = 0): Image
    {
        $brightness = $this->fixRange($brightness, -100, 100) + 100;
        $saturation = $this->fixRange($saturation, -100, 100) + 100;
        $hue = $this->fixRange($hue, -100, 100) + 100;

        $image->setIteratorIndex(0);
        do {
            $image->modulateImage($brightness, $saturation, $hue);
        }
        while ($image->nextImage());

        return $image;
    }
}
