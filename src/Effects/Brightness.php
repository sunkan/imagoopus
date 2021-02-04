<?php declare(strict_types=1);

namespace ImagoOpus\Effects;

use ImagoOpus\Image;

class Brightness implements EffectInterface
{
    use ModulateImageTrait;

    private float $brightness;

    public function __construct(float $brightness = 80)
    {
        $this->brightness = $brightness;
    }

    public function run(Image $image): Image
    {
        return $this->modulateImage($image, $this->brightness);
    }
}
