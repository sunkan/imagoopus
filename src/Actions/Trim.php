<?php declare(strict_types=1);

namespace ImagoOpus\Actions;

use ImagoOpus\Image;

/**
 * Action to remove "whitespace" around the image
 */
class Trim implements ActionInterface
{
    private float $fuzz;

    public function __construct(float $fuzz = 0.1)
    {
        $this->fuzz = $fuzz;
    }

    public function run(Image $image): Image
    {
        $fuzz = $this->fuzz;
        if ($fuzz == 0.1) {
            $target = $image->getImagePixelColor(1, 1);
            if ($target->getColorAsString() != 'srgb(255,255,255)' && $target->getColorAsString() != 'rgb(255,255,255)') {
                $fuzz = 0.8;
                $endTarget = $image->getImagePixelColor(1, $image->getImageHeight() - 1);
                if ($endTarget->isSimilar($target, 0)) {
                    $fuzz = 0.1;
                }
            }
        }

        $fuzz = $fuzz * $image->getQuantum();

        $image->setIteratorIndex(0);
        do {
            $image->trimImage($fuzz);
        }
        while ($image->nextImage());

        return $image;
    }
}
