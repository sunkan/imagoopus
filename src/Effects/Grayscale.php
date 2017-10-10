<?php

namespace ImagoOpus\Effects;

use ImagoOpus\Image;

class Grayscale extends ModulateImage
{
    public function run(Image $image)
    {
        $image = $this->modulateImage(0, -100, 0);

        return $image;
    }
}
