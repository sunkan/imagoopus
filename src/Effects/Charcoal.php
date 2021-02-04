<?php declare(strict_types=1);

namespace ImagoOpus\Effects;

use ImagoOpus\Image;

class Charcoal implements EffectInterface
{
    use FixRangeTrait;

    private float $radius;

    public function __construct(float $radius = 2)
    {
        $this->radius = $radius;
    }

    public function run(Image $image): Image
    {
        $radius = $this->fixRange($this->radius, 0, 10);
        $image->setIteratorIndex(0);
        do {
            $image->charcoalImage($radius, 2);
        }
        while ($image->nextImage());

        return $image;
    }
}
