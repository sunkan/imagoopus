<?php

namespace ImagoOpus\Actions;

use ImagoOpus\Image;

abstract class AAction {
    protected $options = [];
    protected $debug = false;
    protected $logger = false;

    public function __construct($options) {
        $this->options = $options;
        if ($this->options['debug']) {
            $this->debug = $this->options['debug'];
            unset($this->options['debug']);
        }
        if ($this->options['logger']) {
            $this->setLogger($this->options['logger']);
            unset($this->options['logger']);
        }
    }
    public function setLogger($logger) {
        $this->logger = $logger;
    }

    abstract public function run(Image $image);
}