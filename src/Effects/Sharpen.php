<?php

namespace ImagoOpus\Effects;

use ImagoOpus\Image;
use ImagoOpus\Actions\AEffect;

class Sharpen extends AEffect
{
    public function run(Image $image)
    {
        $amount = $this->_fixRange($this->options['amount']?:5, 5, 1000);
        $amount = ($amount * 3) / 100;

        $image->setIteratorIndex(0);
        do {
            $image->sharpenimage(0, $amount);
        } while ($image->getNext());

        return $image;
    }
}
