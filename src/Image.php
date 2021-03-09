<?php declare(strict_types=1);

namespace ImagoOpus;

use BadMethodCallException;
use Imagick;
use ImagickPixel;
use ImagoOpus\Values\Text;

/**
 * Class Image
 * @method bool setIteratorIndex(int $index)
 * @method bool transparentPaintImage(mixed $target, float $alpha, float $fuzz, bool $invert)
 * @method bool nextImage()
 * @method bool setImageFormat(string $format)
 * @method ImagickPixel getImagePixelColor(int $x, int $y)
 * @method int getImageWidth()
 * @method int getImageHeight()
 * @method array getImageGeometry()
 * @method bool thumbnailImage(int $columns, int $rows, bool $bestfit = false, bool $fill = false)
 * @method bool cropImage(int $width, int $height, int $x, int $y)
 * @method bool scaleImage(int $cols, int $rows, bool $bestfit = false)
 * @method string getImageFormat()
 * @method bool flipImage()
 * @method bool rotateImage(mixed $background, float $degrees)
 * @method bool compositeImage(Imagick $composite_object, int $composite, int $x, int $y, int $channel = Imagick::CHANNEL_ALL)
 * @method bool cropThumbnailImage(int $width, int $height)
 * @method int getImageOrientation()
 * @method bool setImageOrientation(int $orientation)
 * @method bool roundCorners(float $x_rounding, float $y_rounding, float $stroke_width = 10, float $displace = 5, float $size_correction = -6)
 * @method bool trimImage(float $fuzz)
 * @method blurImage(float|int $radius, int $int)
 * @method modulateImage(float|int $brightness, float|int $saturation, float|int $hue)
 * @method charcoalImage(float $radius, int $int)
 * @method brightnessContrastImage(int $int, float $contrast)
 * @method sigmoidalContrastImage(bool $param, float|int $abs, float $param1)
 * @method colorizeImage(string $string, ImagickPixel $opacityColor)
 * @method gammaImage(float $param)
 * @method contrastImage(int $int)
 * @method negateImage(bool $grey, int $channel = Imagick::CHANNEL_DEFAULT)
 * @method oilPaintImage(float $radius)
 * @method sepiaToneImage(float $threshold)
 * @method sharpenimage(float $radius, float $sigma, int $channel = Imagick::CHANNEL_DEFAULT)
 * @method vignetteImage(float $blackPoint, float $whitePoint, int $x, int $y)
 */
class Image
{
    protected Imagick $imagick;

    /**
     * @param resource $resource
     */
    public static function fromResource($resource): self
    {
        $imagick = new Imagick();
        $imagick->readImageFile($resource);
        return new self($imagick);
    }

    public static function fromPath(string $path): self
    {
        return new self(new Imagick($path));
    }

    public static function createWithText(Text $text): self
    {
        $imagick = new Imagick();

        // Create a new drawing palette
        $draw = new \ImagickDraw();
        $imagick->newImage(500, 150, new ImagickPixel('none'));

        // Set font properties
        $draw->setFont('Helvetica');
        $draw->setFontSize($text->getSize());
        $draw->setFillColor($text->getColor());
        $draw->setFillOpacity($text->getOpacity());
        $draw->setGravity($text->getGravity()->toImagick());

        // Draw text on image
        $imagick->annotateImage($draw, 10, 10, 0, $text->getText());

        return new self($imagick);
    }

    public function __construct(Imagick $imagick)
    {
        $this->imagick = $imagick;
    }

    public function writeImages(string $path, bool $animated = null): bool
    {
        $animated = $animated ?? $this->isAnimated();
        if ($animated) {
            $this->imagick->optimizeImageLayers();
        }
        return $this->imagick->writeImages($path, $animated);
    }

    public function getImagesBlob(): string
    {
        if ($this->isAnimated()) {
            $this->imagick->optimizeImageLayers();
        }

        return $this->imagick->getImagesBlob();
    }

    public function getImagick(): Imagick
    {
        return $this->imagick;
    }

    public function setImagick(\Imagick $im): void
    {
        $this->imagick = $im;
    }

    public function hasAlpha(): bool
    {
        $format = $this->imagick->getImageFormat();
        return in_array($format, ['PNG', 'GIF']);
    }

    public function isAnimated(): bool
    {
        try {
            $format = $this->imagick->getImageFormat();
            if ($format == 'GIF') {
                return true;
            }
        }
        catch (\ImagickException $e) {
        }
        return false;
    }

    public function getHexColor(\ImagickPixel $pixel): string
    {
        $color = $pixel->getColor();

        return sprintf(
            '#%s%s%s',
            dechex($color['r']),
            dechex($color['g']),
            dechex($color['b'])
        );
    }

    /**
     * @param int $count
     * @return ImagickPixel[]
     */
    public function getImageHistogram(int $count = 0): array
    {
        if (!$count) {
            return $this->imagick->getImageHistogram();
        }

        throw new \RuntimeException('Not implemented');
    }

    public function getQuantum(): int
    {
        $quantum = $this->imagick->getQuantumRange();
        return $quantum['quantumRangeLong'];
    }

    public function getRatio(): float
    {
        $dim = $this->imagick->getImageGeometry();
        return $dim['width'] / $dim['height'];
    }

    /**
     * Returns images file size
     */
    public function getSize(): int
    {
        return $this->imagick->getImageLength();
    }

    public function preform(Actions\ActionInterface $action): Image
    {
        return $action->run($this);
    }

    /**
     * @param array<int, mixed> $args
     * @return false|mixed
     */
    public function __call(string $method, array $args)
    {
        if (method_exists($this->imagick, $method)) {
            return $this->imagick->$method(...$args);
        }
        throw new BadMethodCallException("No method by that name");
    }

    public function compositeColor(string $color, int $mode = Imagick::COMPOSITE_DEFAULT): Image
    {
        $layer = new Imagick();
        $layer->newImage($this->imagick->getImageWidth(), $this->imagick->getImageHeight(), new ImagickPixel($color));
        $layer->setImageFormat('jpg');
        $this->imagick->compositeImage($layer, $mode, 0, 0);
        return $this;
    }

    public function baseCurve(int $channel, int $value, bool $output = false): Image
    {
        $expression = 'u+';
        if ($output == true) {
            $expression .= '(' . (string)$value . '/255)';
        }
        else {
            $expression .= '(u*' . (string)$value . '/255)';
        }
        $this->imagick = $this->imagick->fxImage($expression, $channel);
        return $this;
    }

    public function levels(float $black, float $gamma, float $white): Image
    {
        $quantumRangeLong = $this->getQuantum();
        $this->imagick->levelImage(
            $black * $quantumRangeLong / 255,
            $gamma,
            $white * $quantumRangeLong / 255,
            Imagick::CHANNEL_ALL
        );
        return $this;
    }

    /**
     * @param ImagickPixel|string $color
     */
    public function colortone($color, int $level, bool $negate = true): Image
    {
        $this->imagick->setImageColorspace(Imagick::COLORSPACE_RGB);
        $args = [$level, 100 - $level];

        $opacityColor = new ImagickPixel("rgba(0, 0, 0, 100)");
        $layer1 = clone $this->imagick;
        $layer1->colorizeImage($color, $opacityColor);

        $layer2 = clone $this->imagick;
        $layer2->setImageColorspace(Imagick::COLORSPACE_GRAY);
        if ($negate) {
            $layer2->negateImage(false);
        }

        $this->imagick->setOption('compose:args', $args[0] . ',' . $args[1]);

        $this->imagick->compositeImage($layer1, Imagick::COMPOSITE_BLEND, 0, 0);
        $this->imagick->compositeImage($layer2, Imagick::COMPOSITE_BLEND, 0, 0);

        return $this;
    }

    public function customVignette(string $color1 = 'none', string $color2 = 'black', float $cropFactor = 1.5): Image
    {
        $dim = $this->imagick->getImageGeometry();
        $cropX = floor($dim['width'] * $cropFactor);
        $cropY = floor($dim['height'] * $cropFactor);

        $layer = new Imagick();
        $layer->newPseudoImage((int)$cropX, (int)$cropY, 'radial-gradient:' . $color1 . '-' . $color2);

        $layer->cropThumbnailImage($dim['width'], $dim['height']);
        $layer->setImagePage($dim['width'], $dim['height'], 0, 0);

        $this->imagick->compositeImage($layer, Imagick::COMPOSITE_MULTIPLY, 0, 0);
        $this->imagick->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);

        return $this;
    }
}
