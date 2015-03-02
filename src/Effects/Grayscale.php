<?php

namespace ImagoOpus\Efftects;

use ImagoOpus\Image;


class Grayscale extends ModuleateImage {
    public function run(Image $image) {
        $image = $this->modulateImage(0, -100, 0);

        return $image;
    }
}