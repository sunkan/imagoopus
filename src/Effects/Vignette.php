<?php declare(strict_types=1);

namespace ImagoOpus\Effects;

use ImagoOpus\Image;

class Vignette implements EffectInterface
{
    use FixRangeTrait;

    private float $blur;
    private int $x;
    private int $y;

    public function __construct(float $blur = 10, int $x = 10, int $y = 10)
    {
        $this->blur = $blur;
        $this->x = $x;
        $this->y = $y;
    }

    public function run(Image $image): Image
    {
        $sigma = 10;
        $blur = $this->fixRange($this->blur, 0, 255);
        $x = $this->fixRange($this->x, 0, 1000);
        $y = $this->fixRange($this->y, 0, 1000);

        $image->setIteratorIndex(0);
        do {
            $image->vignetteImage($blur, $sigma, (int)$x, (int)$y);
        }
        while ($image->nextImage());

        return $image;
    }
}
