<?php

namespace ImagoOpus;

use Imagick;

class Image {
    protected $imagick;
    protected $source;
    public function __construct($src) {
        $this->source = $src;
        $this->imagick = new Imagick($src);
    }

    public function writeImages($path, $ani=-1) {
        if (!in_array($ani, [true, false])) {
            $ani = false;
            if ($this->isAnimated()) {
                $ani = true;
            }
        }
        return $this->imagick->writeImages($path, $ani);
    }
    public function getSource() {
        return $this->source;
    }

    public function getImagick() {
        return $this->imagick;
    }
    public function setImagick(\Imagick $im) {
        $this->imagick = $im;
    }

    public function hasAlpha() {
        $format = $this->imagick->getImageFormat();
        return in_array($format, ['PNG', 'GIF']);
    }

    public function isAnimated() {
        $format = $this->imagick->getImageFormat();
        if ($format == 'GIF') {
            return true;
        }
    }
    public function getHexColor(\ImagickPixel $pixel) {
        $color = $pixel->getColor();

        return sprintf('#%s%s%s', 
            dechex($color['r']), 
            dechex($color['g']),
            dechex($color['b'])
        );
    }
    public function getImageHistogram($count=0) {
        if (!$count) {
            return $this->imagick->getImageHistogram();
        }

        exec('convert '.$this->getSource().' -colors '.(int)$count.' -format "%c" histogram:info:', $output);
        $sort = [];
        $data = [];

        foreach($output as $i => $row) {
            $rs = preg_match('/(\d+):\s*\(\s*(\d{1,3}),\s*(\d{1,3}),\s*(\d{1,3})\)\s*(\#[a-f0-9]{6})\s*s?rgb\(\d{1,3},\d{1,3},\d{1,3}\)/i', $row, $m);
            if ($rs) {
                $sort[$i] = (int)$m[1];
                $data[$i] = new \ImagickPixel($m[5]);
                $data[$i]->count = (int)$m[1];
            }
        }
        arsort($sort);
        $keys = array_flip($sort);
        $return = [];
        foreach ($keys as $key) {
            $return[] = $data[$key];
        }

        return $return;
    }

    public function getQuantum() {
        $quantum = $this->imagick->getQuantumRange();
        //pow(2, )
        return $quantum['quantumRangeLong'];
    }
    public function getRatio() {
        $dim = $this->imagick->getImageGeometry();
        return $dim['width'] / $dim['height'];
    }


    public function preform(Actions\AAction $action) {
        return $action->run($this);
    }


    public function __call($method, $args) {
        if (method_exists($this->imagick, $method)) {
            return call_user_func_array([$this->imagick, $method], $args);
        }
        throw new BadMethodCallException("No method by that name");
    }
}
