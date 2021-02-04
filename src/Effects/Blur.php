<?php declare(strict_types=1);

namespace ImagoOpus\Effects;

use ImagoOpus\Image;

class Blur implements EffectInterface
{
    use FixRangeTrait;

    private float $radius;

    public function __construct(float $radius)
    {
        $this->radius = $radius;
    }

    public function run(Image $image): Image
    {
        $radius = $this->fixRange($this->radius, 0, 100) / 10;

        $image->setIteratorIndex(0);
        do {
            $image->blurImage($radius, 100);
        }
        while ($image->nextImage());

        return $image;
    }
}
