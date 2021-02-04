<?php declare(strict_types=1);

namespace ImagoOpus\Actions;

use ImagoOpus\Image;

/**
 * Action to crop image from center of image
 */
class CenterCrop implements ActionInterface
{
    private int $baseWidth;

    public function __construct(int $width = 480)
    {
        $this->baseWidth = $width;
    }

    public function run(Image $image): Image
    {
        $dim = $image->getImageGeometry();
        $ratio = $image->getRatio();
        $image->setIteratorIndex(0);
        if ($ratio > 1 && $ratio > 0.5) {
            if ($dim['width'] > 900) {
                do {
                    $image->thumbnailImage(900, 0);
                }
                while ($image->nextImage());
                $image->setIteratorIndex(0);

                $dim = $image->getImageGeometry();
            }
            $y = (int)(ceil($dim['width'] / 2) - floor($this->baseWidth / 2));
            do {
                $image->cropImage($this->baseWidth, $dim['height'], $y, 0);
            }
            while ($image->nextImage());
        }
        else {
            do {
                $image->thumbnailImage($this->baseWidth, 0);
            }
            while ($image->nextImage());

            $dim = $image->getImageGeometry();

            if ($ratio <= 0.5 && $dim['height'] > 1000) {
                $image->setIteratorIndex(0);
                do {
                    $image->cropImage($this->baseWidth, 950, 0, 0);
                }
                while ($image->nextImage());
            }
        }
        return $image;
    }
}
