<?php

namespace ImagoOpus\Effects;

use ImagoOpus\Image;

class Saturation extends ModulateImage
{
    public function run(Image $image)
    {
        $saturation = $this->options['saturation'] ?: 80;

        $image = $this->modulateImage(0, $saturation, 0);

        return $image;
    }
}
