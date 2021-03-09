<?php declare(strict_types=1);

namespace ImagoOpus\Values;

use ImagoOpus\Image;

final class Gravity
{
    public const CENTER = 'c';
    public const WEST = 'w';
    public const SOUTH = 's';
    public const NORTH = 'n';
    public const EAST = 'e';
    public const NORTH_EAST = 'ne';
    public const NORTH_WEST = 'nw';
    public const SOUTH_WEST = 'sw';
    public const SOUTH_EAST = 'se';
    private const IMAGICK_GRAVITY = [
        self::CENTER => \Imagick::GRAVITY_CENTER,
        self::WEST => \Imagick::GRAVITY_WEST,
        self::SOUTH => \Imagick::GRAVITY_SOUTH,
        self::NORTH => \Imagick::GRAVITY_NORTH,
        self::EAST => \Imagick::GRAVITY_EAST,
        self::NORTH_EAST => \Imagick::GRAVITY_NORTHEAST,
        self::NORTH_WEST => \Imagick::GRAVITY_NORTHWEST,
        self::SOUTH_WEST => \Imagick::GRAVITY_SOUTHWEST,
        self::SOUTH_EAST => \Imagick::GRAVITY_SOUTHEAST,
    ];
    private string $gravity;

    public function __construct(string $gravity)
    {
        $this->gravity = $gravity;
    }

    public function toImagick(): int
    {
        return self::IMAGICK_GRAVITY[$this->gravity];
    }

    public function getGravityCords(Image $image1, Image $image2): array
    {
        $imgWidth = $image1->getImageWidth();
        $imgHeight = $image1->getImageHeight();
        $gWidth = $image2->getImageWidth();
        $gHeight = $image2->getImageHeight();

        $x = $y = 0;
        switch ($this->gravity) {
            case self::CENTER:
                $x = ($imgWidth / 2) - ($gWidth / 2);
                $y = ($imgHeight / 2) - ($gHeight / 2);
                break;
            case self::WEST:
                $y = ($imgHeight / 2) - ($gHeight / 2);
                break;
            case self::EAST:
                $x = $imgWidth - $gWidth;
                $y = ($imgHeight / 2) - ($gHeight / 2);
                break;
            case self::NORTH:
                $x = ($imgWidth / 2) - ($gWidth / 2);
                break;
            case self::NORTH_EAST:
                $x = $imgWidth - $gWidth;
                break;
            case self::SOUTH:
                $x = ($imgWidth / 2) - ($gWidth / 2);
                $y = $imgHeight - $gHeight;
                break;
            case self::SOUTH_WEST:
                $y = $imgHeight - $gHeight;
                break;
            case self::SOUTH_EAST:
                $y = $imgHeight - $gHeight;
                $x = $imgWidth - $gWidth;
                break;
            case self::NORTH_WEST:
                //do nothing
                break;
        }
        return [(int)$x, (int)$y];
    }
}
