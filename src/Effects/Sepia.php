<?php declare(strict_types=1);

namespace ImagoOpus\Effects;

use ImagoOpus\Image;

class Sepia implements EffectInterface
{
    use FixRangeTrait;

    private float $threshold;

    public function __construct(float $threshold = 80)
    {
        $this->threshold = $threshold;
    }

    public function run(Image $image): Image
    {
        $sepia = $this->fixRange($this->threshold, 0, $image->getQuantum());
        $image->setIteratorIndex(0);
        do {
            $image->sepiaToneImage($sepia);
        }
        while ($image->nextImage());

        return $image;
    }
}
