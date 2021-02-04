<?php declare(strict_types=1);

namespace ImagoOpus\Actions;

use ImagoOpus\Image;

class Crop implements ActionInterface
{
    private int $width;
    private int $height;
    private int $x;
    private int $y;

    public function __construct(int $width, int $height, int $x, int $y)
    {
        $this->width = $width;
        $this->height = $height;
        $this->x = $x;
        $this->y = $y;
    }

    public function run(Image $image): Image
    {
        $image->setIteratorIndex(0);

        do {
            $image->cropImage($this->width, $this->height, $this->x, $this->y);
        }
        while ($image->nextImage());

        return $image;
    }
}
