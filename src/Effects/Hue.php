<?php declare(strict_types=1);

namespace ImagoOpus\Effects;

use ImagoOpus\Image;

class Hue implements EffectInterface
{
    use ModulateImageTrait;

    private float $hue;

    public function __construct(float $hue = 80)
    {
        $this->hue = $hue;
    }

    public function run(Image $image): Image
    {
        return $this->modulateImage($image, 0, 0, $this->hue);
    }
}
