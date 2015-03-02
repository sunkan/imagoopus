<?php

namespace ImagoOpus\Actions;

use ImagoOpus\Image;

abstract class AAction implements Serializable {
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

    public function serialize() {
        return serialize($this->options);
    }
    public function unserialize($options) {
        $this->options = unserialize($options);
    }


    abstract public function run(Image $image);
}