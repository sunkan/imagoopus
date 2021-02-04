<?php declare(strict_types=1);

namespace ImagoOpus\Actions;

use ImagoOpus\Image;
use ImagickPixel;
use Imagick;

/**
 * Action to free rotate image
 *
 * Default is to auto rotate image from image meta data
 */
class Rotate implements ActionInterface
{
    private ?float $angle;
    private string $background;

    /**
     * @param float|null $angle If angle is null it will be auto rotated to ORIENTATION_TOPLEFT
     * @param string $background Background color if image don't support alpha
     */
    public function __construct(float $angle = null, string $background = '#fff')
    {
        $this->angle = $angle;
        $this->background = $background;
    }

    public function run(Image $image): Image
    {
        if (!$this->angle) {
            return $this->autoRotate($image);
        }
        $currentFormat = $image->getImageFormat();
        if (!$image->hasAlpha()) {
            $image->setImageFormat("png");
        }
        $image->setIteratorIndex(0);
        $tPixel = new ImagickPixel('transparent');
        do {
            $image->rotateImage($tPixel, $this->angle);
        }
        while ($image->nextImage());

        if (!$image->hasAlpha()) {
            $dim = $image->getImageGeometry();
            $background = new Imagick();
            $background->newImage($dim['width'], $dim['height'], new ImagickPixel($this->background));
            $image->compositeImage($background, Imagick::COMPOSITE_DSTATOP, 0, 0);
            $image->setImageFormat($currentFormat);
        }

        return $image;
    }

    protected function autoRotate(Image $image): Image
    {
        $orientation = $image->getImageOrientation();

        switch ($orientation) {
            case Imagick::ORIENTATION_BOTTOMRIGHT:
                // rotate 180 degrees
                $image->rotateImage("#000", 180);
                break;

            case Imagick::ORIENTATION_RIGHTTOP:
                // rotate 90 degrees CW
                $image->rotateImage("#000", 90);
                break;

            case Imagick::ORIENTATION_LEFTBOTTOM:
                // rotate 90 degrees CCW
                $image->rotateImage("#000", -90);
                break;
        }

        $image->setImageOrientation(Imagick::ORIENTATION_TOPLEFT);

        return $image;
    }
}
