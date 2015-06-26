<?php

namespace ImagoOpus\Actions;

use ImagoOpus\Image;
use ImagickPixel;
use Imagick;

class Rotate extends AAction
{
    protected function autoRotate(Image $image)
    {
        $orientation = $image->getImageOrientation();

        switch ($orientation) {
            case Imagick::ORIENTATION_BOTTOMRIGHT:
                $image->rotateimage("#000", 180); // rotate 180 degrees
                break;

            case Imagick::ORIENTATION_RIGHTTOP:
                $image->rotateimage("#000", 90); // rotate 90 degrees CW
                break;

            case Imagick::ORIENTATION_LEFTBOTTOM:
                $image->rotateimage("#000", -90); // rotate 90 degrees CCW
                break;
        }

        $image->setImageOrientation(Imagick::ORIENTATION_TOPLEFT);

        return $image;
    }

    public function run(Image $image)
    {
        if (!isset($this->options['angle'])) {
            return $this->autoRotate($image);
        }
        $angle = $this->options['angle'];

        if (!$image->hasAlpha()) {
            $backgroundColor = $this->options['background']?:'#fff';
            $dim = $image->getImageGeometry();
            $background = new Imagick();
            $background->newImage($dim['width'], $dim['height'], new ImagickPixel($backgroundColor));
            $currentFormat = $image->getImageFormat();
            $image->setImageFormat("png");
        }
        $image->setIteratorIndex(0);
        $tPixel = new ImagickPixel('transparent');
        do {
            $image->rotateimage($tPixel, $angle);
        } while ($image->nextImage());
        
        if (!$image->hasAlpha()) {
            $image->compositeImage($background, Imagick::COMPOSITE_DSTATOP, 0, 0);
            $image->setImageFormat($currentFormat);
        }

        return $image;
    }
}
