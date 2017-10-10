<?php

namespace ImagoOpus\Actions;

use ImagoOpus\Image;

class CenterCrop extends AAction
{
    public function run(Image $image)
    {
        if ($this->debug && $this->logger) {
            $this->logger->log('info', 'center_crop', $this->options);
        }
        $dim = $image->getImageGeometry();
        $ratio = $image->getRatio();
        $baseWidth = $this->options['base_width'] ?: 480;
        $image->setIteratorIndex(0);
        if ($ratio > 1 && $ratio > 0.5) {
            if ($dim['width'] > 900) {
                do {
                    $image->thumbnailImage(900, null);
                } while ($image->nextImage());
                $image->setIteratorIndex(0);

                $dim = $image->getImageGeometry();
            }
            $y = ceil($dim['width'] / 2) - floor($baseWidth/2);
            do {
                $image->cropImage($baseWidth, $dim['height'], $y, 0);
            } while ($image->nextImage());
        } else {
            do {
                $image->thumbnailImage($baseWidth, null);
            } while ($image->nextImage());

            $dim = $image->getImageGeometry();

            if ($ratio <= 0.5 && $dim['height'] > 1000) {
                $image->setIteratorIndex(0);
                do {
                    $image->cropImage($baseWidth, 950, 0, 0);
                } while ($image->nextImage());
            }
        }
        return $image;
    }
}
