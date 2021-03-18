<?php declare(strict_types=1);

namespace ImagoOpus\Actions;

use ImagoOpus\Image;
use ImagoOpus\Values\Gravity;

final class Watermark implements ActionInterface
{
    private Image $watermark;
    private Gravity $gravity;
    private int $margin;

    public function __construct(Image $watermark, Gravity $gravity, int $margin = 5)
    {
        $this->watermark = $watermark;
        $this->margin = $margin;
        $this->gravity = $gravity;
    }

    public function run(Image $image): Image
    {
        // The resize factor can depend on the size of your watermark, so heads up with dynamic size watermarks !
        $watermarkResizeFactor = 6;

        // Retrieve size of the Images to verify how to print the watermark on the image
        $imgWidth = $image->getImageWidth();
        $imgHeight = $image->getImageHeight();
        $watermarkWidth = $this->watermark->getImageWidth();
        $watermarkHeight = $this->watermark->getImageHeight();

        [$x, $y] = $this->gravity->getGravityCords($image, $this->watermark);

        $image->setIteratorIndex(0);
        do {
            $image->compositeImage($this->watermark->getImagick(), \Imagick::COMPOSITE_OVER, $x, $y);
        }
        while ($image->nextImage());

        return $image;
    }
}
