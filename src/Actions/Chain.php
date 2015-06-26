<?php

namespace ImagoOpus\Actions;

use ImagoOpus\Image;
use ArrayAccess;

class Chain extends AAction implements ArrayAccess
{
    protected $chain = [];

    public function offsetExists($offset)
    {
        return isset($this->chain[$offset]);
    }
    public function offsetGet($offset)
    {
        return isset($this->chain[$offset]) ? $this->chain[$offset] : null;
    }
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->chain[] = $value;
        } else {
            $this->chain[$offset] = $value;
        }
    }
    public function offsetUnset($offset)
    {
        unset($this->chain[$offset]);
    }

    public function serialize()
    {
        if (!count($this->chain)) {
            return serialize([]);
        }
        return serialize($this->chain);
    }
    public function unserialize($options)
    {
        $this->chain = unserialize($options);
    }

    public function run(Image $image)
    {
        if (!count($this->chain)) {
            return $image;
        }
        foreach ($this->chain as $action) {
            $image = $action->run($image);
        }
        return $image;
    }
}
