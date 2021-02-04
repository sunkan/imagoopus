<?php declare(strict_types=1);

namespace ImagoOpus\Actions;

use ImagoOpus\Image;
use ImagickPixel;

/**
 * Action to make background transparent
 */
class Transparent implements ActionInterface
{
    public function run(Image $image): Image
    {
        $image->setImageFormat('png');
        $target = $image->getImagePixelColor(1, 1);
        $fuzz = 0.01;
        if ($image->getHexColor($target) != '#ffffff') {
            $hsl = $target->getHSL();
            if ($hsl['saturation'] > 0.4 && $hsl['luminosity'] < 80) {
                return $image;
            }
            else {
                $colors = $image->getImageHistogram(1);
                /** @var ImagickPixel $color */
                $color = $colors[0];
                $hsl = $color->getHSL();
                if ($hsl['saturation'] < 0.3 && $hsl['luminosity'] > 0.85) {
                    return $image;
                }
            }
            $width = $image->getImageWidth();
            $height = $image->getImageHeight();
            $endTarget = $image->getImagePixelColor($width - 1, $height - 1);
            if ($endTarget->getColorAsString() == $target->getColorAsString()) {
                return $this->makeTransparent($image, $target, $fuzz);
            }
            else {
                $corners = [
                    $target,
                    $image->getImagePixelColor($width - 1, 1),
                    $endTarget,
                    $image->getImagePixelColor(1, $height - 1)
                ];
                foreach ($corners as $color) {
                    $baseHsl = $color->getHSL();
                    foreach ($corners as $color2) {
                        $compHsl = $color2->getHSL();
                        $diff = $baseHsl['luminosity'] - $compHsl['luminosity'];
                        if ($diff > 0.5 || $diff < -0.5) {
                            return $image;
                        }
                    }
                }
                $fuzz = 0.03;
                foreach ($corners as $color) {
                    $image = $this->makeTransparent($image, $color, $fuzz);
                }

                return $image;
            }
        }

        return $this->makeTransparent($image, $target, $fuzz);
    }

    private function makeTransparent(Image $image, ImagickPixel $target, float $fuzz): Image
    {
        $image->setIteratorIndex(0);
        $fuzz = $image->getQuantum() * $fuzz;
        do {
            $image->transparentPaintImage($target, 0, $fuzz, false);
        }
        while ($image->nextImage());

        return $image;
    }
}
