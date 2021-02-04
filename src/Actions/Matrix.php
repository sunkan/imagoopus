<?php declare(strict_types=1);

namespace ImagoOpus\Actions;

use Imagick;
use ImagoOpus\Image;
use ImagickPixel;

class Matrix implements ActionInterface
{
    /** @var array<int, float>  */
    private array $matrix;
    private string $background;

    /**
     * @param array<int, float> $matrix
     * @param string $background Background color if image don't support alpha
     */
    public function __construct(array $matrix, string $background = '#fff')
    {
        $this->matrix = $matrix;
        $this->background = $background;
    }

    public function run(Image $image): Image
    {
        $matrix = $this->matrix;

        $angle = atan2($matrix[1], $matrix[0]) * (180 / M_PI);

        $skewX = tan($matrix[2]) * (180 / M_PI);
        $skewY = tan($matrix[3]) * (180 / M_PI);

        $skew = atan2($matrix[3], $matrix[2]) * (180 / M_PI);
        $skew = round($skew - $angle);
        if ($skew == -90) {
            $image->flipImage();
        }

        $image->setIteratorIndex(0);
        $tPixel = new ImagickPixel('transparent');
        do {
            $image->rotateImage($tPixel, $angle);
        }
        while ($image->nextImage());

        if (!$image->hasAlpha()) {
            $dim = $image->getImageGeometry();
            $background = new Imagick();
            $background->newImage($dim['width'], $dim['height'], new ImagickPixel($this->background));
            $currentFormat = $image->getImageFormat();
            $image->setImageFormat("png");
            $image->compositeImage($background, Imagick::COMPOSITE_DSTATOP, 0, 0);
            $image->setImageFormat($currentFormat);
        }

        return $image;
    }
}
