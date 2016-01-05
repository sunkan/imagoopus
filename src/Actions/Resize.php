<?php

namespace ImagoOpus\Actions;

use ImagoOpus\Image;

class Resize extends AAction
{
    public function run(Image $image)
    {
        if ($this->debug && $this->logger) {
            $this->logger->info('resize image (best fit): '. 'width:'.$this->options['width'].':height:'.$this->options['height']);
        }
        $dim = $image->getImageGeometry();
        $width = $this->options['width'];
        $height = $this->options['height'];

        if ($width <= $dim['width'] && $height <= $dim['height']) {
            $image->setIteratorIndex(0);
            if (isset($this->options['square']) || $this->options['type'] == 'square') {
                do {
                    $image->cropThumbnailImage($width?$width:$height, $height?$height:$width);
                } while ($image->nextImage());
            } elseif ($this->options['force'] || $this->options['type']=='force') {
                $bywidth = (($width/$dim['width']) < ($height/$dim['height'])) ? true : false;
                if ($this->options['force']=='width') {
                    $height = null;
                } elseif ($this->options['force']=='height') {
                    $width = null;
                } else {
                    if ($bywidth) {
                        $height = null;
                    } else {
                        $width = null;
                    }
                }
                do {
                    $image->thumbnailImage($width, $height);
                } while ($image->nextImage());
            } elseif ($this->options['width'] || $this->options['height']) {
                do {
                    $image->scaleImage($this->options['width'], $this->options['height']);
                } while ($image->nextImage());
            }
        }
        return $image;
    }
}
