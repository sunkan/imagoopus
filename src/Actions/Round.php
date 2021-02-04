<?php declare(strict_types=1);

namespace ImagoOpus\Actions;

use ImagoOpus\Image;
use Imagick;
use ImagickPixel;

/**
 * Action to round the corner of the image
 */
class Round implements ActionInterface
{
    /** @var float|string */
    private $radius;
    private string $background;

    /**
     * @param float|string $radius Can be specified by a number or as a string that defines percentages
     * @param string $background Background color if image don't support alpha
     */
    public function __construct($radius, string $background = '#fff')
    {
        $this->radius = $radius;
        $this->background = $background;
    }

    public function run(Image $image): Image
    {
        $dim = $image->getImageGeometry();
        $radiusX = $radiusY = $this->radius;
        if (is_string($this->radius)) {
            $percent = (int)$radiusX / 100;
            $radiusX = $dim['width'] * $percent;
            $radiusY = $dim['height'] * $percent;
        }

        $currentFormat = $image->getImageFormat();
        if (!$image->hasAlpha()) {
            $image->setImageFormat("png");
        }

        $image->setIteratorIndex(0);
        do {
            $image->roundCorners((float)$radiusX, (float)$radiusY);
        }
        while ($image->nextImage());

        if (!$image->hasAlpha()) {
            $background = new Imagick();
            $background->newImage($dim['width'], $dim['height'], new ImagickPixel($this->background));
            $image->compositeImage($background, Imagick::COMPOSITE_DSTATOP, 0, 0);
            $image->setImageFormat($currentFormat);
        }

        return $image;
    }
}
