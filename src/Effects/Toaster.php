<?php declare(strict_types=1);

namespace ImagoOpus\Effects;

use ImagoOpus\Image;

class Toaster implements EffectInterface
{
    use ModulateImageTrait;

    public function run(Image $image): Image
    {
        $image->colortone('#330000', 100, false);

        $image = $this->modulateImage($image, 150, 80, 100);

        $image->gammaImage(1.2);

        $image->contrastImage(1);
        $image->contrastImage(1);

        $image->customVignette('none', 'LavenderBlush3');
        $image->customVignette('#ff9966', 'none');

        return $image;
    }
}
