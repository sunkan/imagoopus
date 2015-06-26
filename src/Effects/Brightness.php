<?php

namespace ImagoOpus\Efftects;

use ImagoOpus\Image;

class Brightness extends ModuleateImage
{
    public function run(Image $image)
    {
        $brightness = $this->options['brightness']?:80;

        $image = $this->modulateImage(0, 0, $brightness);
        return $image;
    }
}
