<?php

namespace ImagoOpus\Effects;

use ImagoOpus\Image;

class Brightness extends ModulateImage
{
    public function run(Image $image)
    {
        $brightness = $this->options['brightness'] ?: 80;

        $image = $this->modulateImage(0, 0, $brightness);
        return $image;
    }
}
