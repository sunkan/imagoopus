<?php

namespace ImagoOpus\Actions;

use ImagoOpus\Image;

class GravityResize extends AAction
{
    public function run(Image $image)
    {
        $width = $this->options['width'];
        $height = $this->options['height'];

        $image->setIteratorIndex(0);

        if ($width || $height) {
            do {
                $image->scaleImage($width?:0, $width?0:$height);
            } while ($image->nextImage());
        }

        $imgWidth = $image->getImageWidth();
        $imgHeight = $image->getImageHeight();

        $gWidth = $this->options['gravity_width']?:$width;
        $gHeight = $this->options['gravity_height']?:$height;

        $x = $y = 0;
        switch ($this->options['gravity']) {
            case 'c':
                //center
                $x = ($imgWidth/2)-($gWidth/2);
                $y = ($imgHeight/2)-($gHeight/2);
                break;
            case 'w':
                //west
                $y = ($imgHeight/2)-($gHeight/2);
                break;
            case 'e':
                //east
                $x = $imgWidth-$gWidth;
                $y = ($imgHeight/2)-($gHeight/2);
                break;
            case 'n':
                //north
                $x = ($imgWidth/2)-($gWidth/2);
                break;
            case 'nw':
                //northwest
                break;
            case 'ne':
                //northeast
                $x = $imgWidth-$gWidth;
                break;
            case 's':
                //south
                $x = ($imgWidth/2)-($gWidth/2);
                $y = $imgHeight-$gHeight;
                break;
            case 'sw':
                //southwest
                $y = $imgHeight-$gHeight;
                break;
            case 'se':
                //southeast
                $y = $imgHeight-$gHeight;
                $x = ($imgWidth/2)-$gWidth;
                break;
            default:
                //throw something
                break;
        }
        $image->setIteratorIndex(0);

        do {
            $image->cropImage($gWidth, $gHeight, $x, $y);
        } while ($image->nextImage());

        return $image;
    }
}
