<?php

namespace ImagoOpus;

use Imagick;

class Image {
    protected $imagick;
    public function __construct($src) {
        $this->imagick = new Imagick($src);
    }

    public function getImagick() {
        return $this->imagick;
    }
    public function setImagick(\Imagick $im) {
        $this->imagick = $im;
    }


    public function isAnimated() {
        $format = $this->imagick->getImageFormat();
        if ($format == 'GIF') {
            return true;
        }
    }

    public function getQuantum() {
        $quantum = $this->imagick->getQuantumRange();
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