<?php

namespace ImagoOpus\Efftects;

use ImagoOpus\Image;

class Hue extends ModuleateImage
{
    public function run(Image $image)
    {
        $hue = $this->options['hue']?:80;

        $image = $this->modulateImage($hue, 0, 0);
        return $image;
    }
}
