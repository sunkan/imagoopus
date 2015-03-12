<?php

namespace ImagoOpus\Effects;

use ImagoOpus\Image;
use ImagickPixel;

/*
convert test.jpg \( -clone 0 -fill '#330000' -colorize 100% \) \( -clone 0 -colorspace gray -negate \) -compose blend -define compose:args=100,0 -composite test.jpg
convert test.jpg -modulate 150,80,100 -gamma 1.2 -contrast -contrast test.jpg
convert test.jpg \( -size 960.0x960.0 radial-gradient:none-LavenderBlush3 -gravity center -crop 640x640+0+0 +repage \) -compose multiply -flatten test.jpg
convert test.jpg \( -size 960.0x960.0 radial-gradient:#ff9966-none -gravity center -crop 640x640+0+0 +repage \) -compose multiply -flatten test.jpg

    $this->tempfile();
    $this->colortone($this->_tmp, '#330000', 100, 0);
 
    $this->execute("convert $this->_tmp -modulate 150,80,100 -gamma 1.2 -contrast -contrast $this->_tmp");
     
    $this->vignette($this->_tmp, 'none', 'LavenderBlush3');
    $this->vignette($this->_tmp, '#ff9966', 'none');

*/

class Toaster extends ModulateImage {
    public function run(Image $image) {
        $image->colortone('#330000', 100, 0);

        $image = $this->modulateImage($image, 150, 80, 100);

        $image->gammaImage(1.2)

        $image->contrastImage(1);
        $image->contrastImage(1);

        $image->customVignette('none', 'LavenderBlush3');
        $image->customVignette('#ff9966', 'none');

        return $image;
    }
}
