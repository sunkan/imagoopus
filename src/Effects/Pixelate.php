<?php declare(strict_types=1);

namespace ImagoOpus\Effects;

use ImagoOpus\Image;

class Pixelate implements EffectInterface
{
    use FixRangeTrait;

    private float $size;

    public function __construct(float $size = 10)
    {
        $this->size = $size;
    }

    public function run(Image $image): Image
    {
        $pixelSize = $this->fixRange($this->size, 3, 100);
        $dim = $image->getImageGeometry();
        $tmpWidth = $dim['width'] / $pixelSize;
        $tmpHeight = $dim['height'] / $pixelSize;

        $image->setIteratorIndex(0);
        do {
            $image->scaleImage((int)$tmpWidth, (int)$tmpHeight);
            $image->scaleImage($dim['width'], $dim['height']);
        }
        while ($image->nextImage());

        return $image;
    }
}
