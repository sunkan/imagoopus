<?php

namespace ImagoOpus\Actions;

use ImagoOpus\Image;

class Trim extends AAction {
    public function run(Image $image) {
        $fuzz = $this->options['fuzz']?:0.1;
        if ($fuzz == 0.1) {
            $target = $image->getImagePixelColor(1, 1);
            if ($target->getColorAsString()!='srgb(255,255,255)' && $target->getColorAsString()!='rgb(255,255,255)') {
                $fuzz = 0.8;
            }
        }
        
        $quantum = $image->getQuantum();

        $image->setIteratorIndex(0);
        do {
            $image->trimImage($fuzz*$quantum);
        } while($image->nextImage());
        
        return $image;
    }
}