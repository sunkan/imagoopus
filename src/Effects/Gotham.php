<?php declare(strict_types=1);

namespace ImagoOpus\Effects;

use ImagoOpus\Image;
use ImagickPixel;

class Gotham implements EffectInterface
{
    use ModulateImageTrait;

    public function run(Image $image): Image
    {
        $image = $this->modulateImage($image, 120, 10, 100);

        $opacityColor = new ImagickPixel("rgba(0, 0, 0, 20)");
        $image->colorizeImage('#222b6d', $opacityColor);

        $image->gammaImage(0.5);

        $image->contrastImage(1);
        $image->contrastImage(1);

        return $image;
    }
}
