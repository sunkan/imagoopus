<?php

namespace ImagoOpus\Actions;

use ImagoOpus\Image;
use Imagick;
use ImagickPixel;

class Round extends AAction
{
    public function run(Image $image)
    {
        $dim = $image->getImageGeometry();
        $radiusX = $radiusY = $this->options['radius'];
        $backgroundColor = $this->options['background']?:'#fff';
        if (strpos($radius, '%')) {
            $proc = (int)$radius/100;
            $radiusX = $dim['width']*$proc;
            $radiusY = $dim['height']*$proc;
        }
        if (!$image->hasAlpha()) {
            $background = new Imagick();
            $background->newImage($dim['width'], $dim['height'], new ImagickPixel($backgroundColor));
            $currentFormat = $image->getImageFormat();
            $image->setImageFormat("png");
        }

        $image->setIteratorIndex(0);
        do {
            $image->roundCorners($radiusX, $radiusY);
        } while ($image->nextImage());

        if (!$image->hasAlpha()) {
            $image->compositeImage($background, Imagick::COMPOSITE_DSTATOP, 0, 0);
            $image->setImageFormat($currentFormat);
        }
        return $image;
    }
}
