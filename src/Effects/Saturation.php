<?php

namespace ImagoOpus\Efftects;

use ImagoOpus\Image;


class Saturation extends ModuleateImage {
    public function run(Image $image) {
        $saturation = $this->options['saturation']?:80;

        $image = $this->modulateImage(0, $saturation, 0);

        return $image;
    }
}