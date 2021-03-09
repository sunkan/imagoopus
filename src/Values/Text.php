<?php declare(strict_types=1);

namespace ImagoOpus\Values;

final class Text
{
    private string $text;
    private int $size;
    private \ImagickPixel $color;
    private float $opacity;
    /**
     * @var Gravity
     */
    private Gravity $gravity;

    public function __construct(
        string $text,
        int $size,
        \ImagickPixel $color,
        Gravity $gravity,
        float $opacity = 1
    ) {
        $this->text = $text;
        $this->size = $size;
        $this->color = $color;
        $this->opacity = $opacity;
        $this->gravity = $gravity;
    }


    public function getText(): string
    {
        return $this->text;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getColor(): \ImagickPixel
    {
        return $this->color;
    }

    public function getOpacity(): float
    {
        return $this->opacity;
    }

    public function getGravity(): Gravity
    {
        return $this->gravity;
    }
}
