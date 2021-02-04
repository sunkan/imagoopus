<?php declare(strict_types=1);

namespace ImagoOpus\Effects;

use ImagoOpus\Image;

class Sharpen implements EffectInterface
{
    use FixRangeTrait;

    private float $amount;

    public function __construct(float $amount = 5)
    {
        $this->amount = $amount;
    }

    public function run(Image $image): Image
    {
        $amount = $this->fixRange($this->amount, 5, 1000);
        $amount = ($amount * 3) / 100;

        $image->setIteratorIndex(0);
        do {
            $image->sharpenimage(0, $amount);
        }
        while ($image->nextImage());

        return $image;
    }
}
