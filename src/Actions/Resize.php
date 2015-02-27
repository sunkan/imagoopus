<?php

namespace ImagoOpus\Actions;

use ImagoOpus\Image;

class Resize extends AAction {
    public function run(Image $image) {
        if ($this->debug && $this->logger) {
            $this->logger->info('resize image (best fit): '. 'width:'.$this->options['width'].':height:'.$this->options['height']);
        }
        $dim = $image->getImageGeometry();
        $width = $this->options['width'];
        $height = $this->options['height'];

        if ($width <= $dim['width'] && $height <= $dim['height']){
            if (isset($this->options['square']) || $this->options['type']=='square') {
                if ($image->isAnimated()){
                    $imagick = $image->coalesceImages();
                    foreach($imagick as $frame){
                        $frame->cropThumbnailImage($width?$width:$height,$height?$height:$width);
                    }
                    $image->setImagick($imagick->deconstructImages());
                } else {
                    $image->cropThumbnailImage($width?$width:$height,$height?$height:$width);
                }
            } elseif ($this->options['force'] || $this->options['type']=='force') {
                $bywidth = (($width/$dim['width']) < ($height/$dim['height'])) ? true : false;
                if ($this->options['force']=='width'){
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
                if ($image->isAnimated()){
                    $imagick = $image->coalesceImages();
                    foreach($imagick as $frame){
                        $frame->thumbnailImage($width, $height);
                    }
                    $image->setImagick($imagick->deconstructImages());
                } else {
                    $image->thumbnailImage($width, $height);
                }
            } elseif($this->options['width'] || $this->options['height']) {
                if ($image->isAnimated()){
                    $imagick = $image->coalesceImages();
                    foreach($imagick as $frame){
                        $frame->scaleImage($this->options['width'], $this->options['height']);
                    }
                    $image->setImagick($imagick->deconstructImages());
                } else {
                    $image->scaleImage($this->options['width'], $this->options['height']);
                }
            }
        }
        return $image;
    }
}