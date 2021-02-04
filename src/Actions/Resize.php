<?php declare(strict_types=1);

namespace ImagoOpus\Actions;

use ImagoOpus\Image;

class Resize implements ActionInterface
{
    public const FORCE_TYPE_WIDTH = 'width';
    public const FORCE_TYPE_HEIGHT = 'height';

    public const TYPE_SQUARE = 'square';
    public const TYPE_FORCE = 'force';
    public const TYPE_NONE = '';

    private const ALLOWED_TYPES = [
        self::TYPE_SQUARE,
        self::TYPE_FORCE,
        self::TYPE_NONE,
    ];

    private int $width;
    private int $height;
    private string $type;
    private ?string $forceType;

    public function __construct(int $width, int $height, string $type = self::TYPE_NONE, string $forceType = null)
    {
        if (!in_array($type, self::ALLOWED_TYPES, true)) {
            throw new \InvalidArgumentException('Invalid resize type');
        }
        $this->width = $width;
        $this->height = $height;
        $this->type = $type;
        $this->forceType = $forceType;
    }

    public function run(Image $image): Image
    {
        $dim = $image->getImageGeometry();
        $width = $this->width;
        $height = $this->height;

        if ($width <= $dim['width'] && $height <= $dim['height']) {
            $image->setIteratorIndex(0);
            if ($this->type === self::TYPE_SQUARE) {
                do {
                    $image->cropThumbnailImage($width ? $width : $height, $height ? $height : $width);
                }
                while ($image->nextImage());
            }
            elseif ($this->type === self::TYPE_FORCE) {
                $byWidth = ($width / $dim['width']) < ($height / $dim['height']);
                if ($this->forceType === self::FORCE_TYPE_WIDTH) {
                    $height = 0;
                }
                elseif ($this->forceType === self::FORCE_TYPE_HEIGHT) {
                    $width = 0;
                }
                else {
                    if ($byWidth) {
                        $height = 0;
                    }
                    else {
                        $width = 0;
                    }
                }
                do {
                    $image->thumbnailImage($width, $height);
                }
                while ($image->nextImage());
            }
            elseif ($width || $height) {
                do {
                    $image->scaleImage($width, $height);
                }
                while ($image->nextImage());
            }
        }
        return $image;
    }
}
