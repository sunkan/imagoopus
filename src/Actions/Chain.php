<?php declare(strict_types=1);

namespace ImagoOpus\Actions;

use ImagoOpus\Image;
use ArrayAccess;

/**
 * @implements ArrayAccess<int, ActionInterface>
 */
class Chain implements ArrayAccess, ActionInterface
{
    /** @var ActionInterface[] */
    protected array $chain = [];

    public function __construct(ActionInterface ...$actions)
    {
        $this->chain = $actions;
    }

    public function offsetExists($offset): bool
    {
        return isset($this->chain[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->chain[$offset]) ? $this->chain[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        if (!$value instanceof ActionInterface) {
            throw new \InvalidArgumentException('Invalid action');
        }
        if (is_null($offset)) {
            $this->chain[] = $value;
        }
        else {
            $this->chain[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->chain[$offset]);
    }

    public function run(Image $image): Image
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
