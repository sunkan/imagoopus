<?php

namespace ImagoOpus\Actions;

use ImagoOpus\Image;
use ImagickPixel;

class Matrix extends AAction {
    public function run(Image $image) {
        $matrix = $this->options['matrix'];

        $angle = atan2($matrix[1], $matrix[0]) * (180/M_PI);

        $skewX = tan($matrix[2]) * (180/M_PI);
        $skewY = tan($matrix[3]) * (180/M_PI);

        $skew = atan2($matrix[3], $matrix[2]) * (180/M_PI);
        $skew = round($skew - $angle);
        if ($skew==-90) {
            $image->flipImage();
        }

        $image->rotateimage(new ImagickPixel('transparent'), $angle);

        return $image;
    }
}