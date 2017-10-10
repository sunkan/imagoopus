<?php

namespace ImagoOpus\Actions;

use ImagoOpus\Image;

class Crop extends AAction
{
    public function run(Image $image)
    {
        $crop = $this->options;

        if ($this->debug && $this->logger) {
            $log = [
                'type' => 'crop image',
                'data' => $crop
            ];
            $this->logger->info(json_encode($log));
        }
        $image->setIteratorIndex(0);

        do {
            $image->cropImage((int) $crop['width'], (int) $crop['height'], (int) $crop['x'], (int) $crop['y']);
        } while ($image->nextImage());

        return $image;
    }
}
