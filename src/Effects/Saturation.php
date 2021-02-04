<?php declare(strict_types=1);

namespace ImagoOpus\Effects;

use ImagoOpus\Image;

class Saturation implements EffectInterface
{
    use ModulateImageTrait;

    private int $saturation;

    public function __construct(int $saturation = 80)
    {
        $this->saturation = $saturation;
    }

    public function run(Image $image): Image
    {
        return $this->modulateImage($image, 0, $this->saturation);
    }
}
