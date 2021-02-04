<?php declare(strict_types=1);

namespace ImagoOpus\Effects;

use ImagoOpus\Image;

class Grayscale implements EffectInterface
{
    use ModulateImageTrait;

    public function run(Image $image): Image
    {
        return $this->modulateImage($image, 100);
    }
}
