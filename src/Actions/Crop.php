<?php

namespace ImagoOpus\Actions;

use ImagoOpus\Image;

class Crop extends AAction {
    public function run(Image $image) {
        $crop = $this->options;

        if ($this->debug && $this->logger) {
            $log = [
                'type'=>'crop image',
                'data'=>$crop
            ];
            $this->logger->info(json_encode($log));
        }

        if ($image->isAnimated()){
            $imagickImage = $image->getImagick();
            $imagickImage = $imagickImage->coalesceImages();
            foreach($imagickImage as $frame){
                $frame->cropImage($crop['width'], $crop['height'], $crop['x'], $crop['y']);
            }
            $image->setImagick($imagickImage->deconstructImages());
        } else {
            $image->cropImage((int)$crop['width'], (int)$crop['height'], (int)$crop['x'], (int)$crop['y']);
        }
        return $image;
    }
}