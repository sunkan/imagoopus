<?php

namespace ImagoOpus\Effects;

use ImagoOpus\Image;
use ImagickPixel;

/*
    $this->tempfile();
     
    $command = "convert {$this->_tmp} -channel R -level 33% -channel G -level 33% $this->_tmp";
     
    $this->execute($command);
    $this->vignette($this->_tmp);
     
    $this->output();
*/

class Lomo extends ModulateImage
{
    public function run(Image $image)
    {
        $image->colortone('#330000', 100, 0);

        $image = $this->modulateImage($image, 150, 80, 100);

        $image->gammaImage(1.2);

        $image->contrastImage(1);
        $image->contrastImage(1);

        $image->customVignette('none', 'LavenderBlush3');
        $image->customVignette('#ff9966', 'none');

        return $image;
    }
}
