<?php

namespace ImagoOpus\Actions;

use Imagick;
use ImagoOpus\Image;
use ImagickPixel;

class Matrix extends AAction
{
    public function run(Image $image)
    {
        $matrix = $this->options['matrix'];

        $angle = atan2($matrix[1], $matrix[0]) * (180 / M_PI);

        $skewX = tan($matrix[2]) * (180 / M_PI);
        $skewY = tan($matrix[3]) * (180 / M_PI);

        $skew = atan2($matrix[3], $matrix[2]) * (180 / M_PI);
        $skew = round($skew - $angle);
        if ($skew==-90) {
            $image->flipImage();
        }

        $image->setIteratorIndex(0);
        $tPixel = new ImagickPixel('transparent');
        do {
            $image->rotateImage($tPixel, $angle);
        } while ($image->nextImage());

        if (!$image->hasAlpha()) {
            $backgroundColor = $this->options['background']?:'#fff';
            $dim = $image->getImageGeometry();
            $background = new Imagick();
            $background->newImage($dim['width'], $dim['height'], new ImagickPixel($backgroundColor));
            $currentFormat = $image->getImageFormat();
            $image->setImageFormat("png");
            $image->compositeImage($background, Imagick::COMPOSITE_DSTATOP, 0, 0);
            $image->setImageFormat($currentFormat);
        }

        return $image;
    }
}
