<?php declare(strict_types=1);

namespace ImagoOpus\Actions;

use ImagoOpus\Image;

interface ActionInterface
{
    public function run(Image $image): Image;
}
