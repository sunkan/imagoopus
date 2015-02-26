<?php

namespace ImagoOpus\Actions;

use ImagoOpus\Image;

class Trim extends AAction {
    public function run(Image $image) {
        $fuzz = $this->options['fuzz']?:0.1;
        $quantum = $image->getQuantum();
        $image->trimImage($fuzz*$quantum);
        return $image;
    }
}