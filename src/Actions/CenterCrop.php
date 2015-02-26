<?php

namespace ImagoOpus\Actions;

use ImagoOpus\Image;

class CenterCrop extends AAction {

    public function run(Image $image) {
        if ($this->debug && $this->logger) {
            $this->logger('info', 'center_crop', $this->options);
        }
        $dim = $image->getImageGeometry();
        $ratio = $image->getRatio();
        $baseWidth = $this->options['base_width']?:480;

        if ($ratio > 1 && $ratio > 0.5)  {
            if ($dim['width']>900) {
                $image->thumbnailImage(900, null);
                $dim = $image->getImageGeometry();
            }
            $y = ceil($dim['width']/2) - floor($baseWidth/2);

            $image->cropImage($baseWidth, $dim['height'], $y, 0);
        } else {
            $image->thumbnailImage($baseWidth, null);
            $dim = $image->getImageGeometry();
            if ($ratio <= 0.5 && $dim['height']>1000) {
                $image->cropImage($baseWidth, 950, 0, 0);
            }
        }
        return $image;
    }

}