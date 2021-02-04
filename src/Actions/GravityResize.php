<?php declare(strict_types=1);

namespace ImagoOpus\Actions;

use ImagoOpus\Image;

/**
 * Resize image with a specific gravity
 *
 * Instead of always resizing image from top left with this action you can specify what area of
 * the image is important
 */
class GravityResize implements ActionInterface
{
    public const GRAVITY_CENTER = 'c';
    public const GRAVITY_WEST = 'w';
    public const GRAVITY_SOUTH = 's';
    public const GRAVITY_NORTH = 'n';
    public const GRAVITY_EAST = 'e';
    public const GRAVITY_NORTH_EAST = 'ne';
    public const GRAVITY_NORTH_WEST = 'nw';
    public const GRAVITY_SOUTH_WEST = 'sw';
    public const GRAVITY_SOUTH_EAST = 'se';
    private const ALLOWED_GRAVITY = [
        self::GRAVITY_CENTER,
        self::GRAVITY_WEST,
        self::GRAVITY_SOUTH,
        self::GRAVITY_NORTH,
        self::GRAVITY_EAST,
        self::GRAVITY_NORTH_EAST,
        self::GRAVITY_NORTH_WEST,
        self::GRAVITY_SOUTH_WEST,
        self::GRAVITY_SOUTH_EAST,
    ];

    private string $gravity;
    private int $width;
    private int $height;
    private ?int $gravityWidth;
    private ?int $gravityHeight;

    public function __construct(
        string $gravity,
        int $width,
        int $height,
        int $gravityWidth = null,
        int $gravityHeight = null
    ) {
        if (!in_array($gravity, self::ALLOWED_GRAVITY, true)) {
            throw new \InvalidArgumentException('Not a valid gravity value');
        }

        $this->gravity = $gravity;
        $this->width = $width;
        $this->height = $height;
        $this->gravityWidth = $gravityWidth;
        $this->gravityHeight = $gravityHeight;
    }

    public function run(Image $image): Image
    {
        $width = $this->width;
        $height = $this->height;

        $image->setIteratorIndex(0);

        if ($width || $height) {
            do {
                $image->scaleImage($width ?: 0, $width ? 0 : $height);
            }
            while ($image->nextImage());
        }

        $imgWidth = $image->getImageWidth();
        $imgHeight = $image->getImageHeight();

        $gWidth = $this->gravityWidth ?: $width;
        $gHeight = $this->gravityHeight ?: $height;

        $x = $y = 0;
        switch ($this->gravity) {
            case self::GRAVITY_CENTER:
                $x = ($imgWidth / 2) - ($gWidth / 2);
                $y = ($imgHeight / 2) - ($gHeight / 2);
                break;
            case self::GRAVITY_WEST:
                $y = ($imgHeight / 2) - ($gHeight / 2);
                break;
            case self::GRAVITY_EAST:
                $x = $imgWidth - $gWidth;
                $y = ($imgHeight / 2) - ($gHeight / 2);
                break;
            case self::GRAVITY_NORTH:
                $x = ($imgWidth / 2) - ($gWidth / 2);
                break;
            case self::GRAVITY_NORTH_EAST:
                $x = $imgWidth - $gWidth;
                break;
            case self::GRAVITY_SOUTH:
                $x = ($imgWidth / 2) - ($gWidth / 2);
                $y = $imgHeight - $gHeight;
                break;
            case self::GRAVITY_SOUTH_WEST:
                $y = $imgHeight - $gHeight;
                break;
            case self::GRAVITY_SOUTH_EAST:
                $y = $imgHeight-$gHeight;
                $x = ($imgWidth / 2) - $gWidth;
                break;
            case self::GRAVITY_NORTH_WEST:
                //do nothing
                break;
        }
        $image->setIteratorIndex(0);

        do {
            $image->cropImage($gWidth, $gHeight, $x, $y);
        }
        while ($image->nextImage());

        return $image;
    }
}
