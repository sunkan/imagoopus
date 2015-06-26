<?php
/**
 * ImagoOpus
 *
 * @package ImagoOpus
 * @author  Andreas Sundqvist <andreas@forme.se>
 */

namespace ImagoOpus;

use Imagick;

class Image
{
    protected static $channels = [
        'red' => Imagick::CHANNEL_RED,
        'green' => Imagick::CHANNEL_GREEN,
        'blue' => Imagick::CHANNEL_BLUE,
    ];
    protected static $composites = [
        'overlay' => Imagick::COMPOSITE_OVERLAY,
        'multiply' => Imagick::COMPOSITE_MULTIPLY
    ];

    protected $imagick;
    protected $source;

    public function __construct($src)
    {
        $this->source = $src;
        $this->imagick = new Imagick($src);
    }

    public function writeImages($path, $ani = -1)
    {
        if (!in_array($ani, [true, false])) {
            $ani = false;
            if ($this->isAnimated()) {
                $ani = true;
            }
        }
        if ($ani) {
            $this->imagick->optimizeImageLayers();
        }
        return $this->imagick->writeImages($path, $ani);
    }

    public function getSource()
    {
        return $this->source;
    }

    public function getImagick()
    {
        return $this->imagick;
    }

    public function setImagick(\Imagick $im)
    {
        $this->imagick = $im;
    }

    public function hasAlpha()
    {
        $format = $this->imagick->getImageFormat();
        return in_array($format, ['PNG', 'GIF']);
    }

    public function isAnimated()
    {
        $format = $this->imagick->getImageFormat();
        if ($format == 'GIF') {
            return true;
        }
    }

    public function getHexColor(\ImagickPixel $pixel)
    {
        $color = $pixel->getColor();

        return sprintf(
            '#%s%s%s',
            dechex($color['r']),
            dechex($color['g']),
            dechex($color['b'])
        );
    }

    public function getImageHistogram($count = 0)
    {
        if (!$count) {
            return $this->imagick->getImageHistogram();
        }
        $source = $this->getSource();
        if (strpos($source, '://')) {
            exec('curl -s "'.$source.'" | convert fd:0 -colors '.(int)$count.' -format "%c" histogram:info:-', $output);
        } else {
            exec('convert "'.$source.'" -colors '.(int)$count.' -format "%c" histogram:info:', $output);
        }

        $sort = [];
        $data = [];

        foreach ($output as $i => $row) {
            $reg = '/(\d+):\s*\(\s*(\d{1,3}),\s*(\d{1,3}),\s*(\d{1,3})\)\s*
                    (\#[a-f0-9]{6})\s*s?rgb\(\d{1,3},\d{1,3},\d{1,3}\)/i';
            $rs = preg_match($reg, $row, $m);
            if ($rs) {
                $sort[$i] = (int)$m[1];
                $data[$i] = new \ImagickPixel($m[5]);
                $data[$i]->count = (int)$m[1];
            }
        }
        arsort($sort);
        $keys = array_flip($sort);
        $return = [];
        foreach ($keys as $key) {
            $return[] = $data[$key];
        }

        return $return;
    }

    public function getQuantum()
    {
        $quantum = $this->imagick->getQuantumRange();
        return $quantum['quantumRangeLong'];
    }

    public function getRatio()
    {
        $dim = $this->imagick->getImageGeometry();
        return $dim['width'] / $dim['height'];
    }

    public function preform(Actions\AAction $action)
    {
        return $action->run($this);
    }

    public function __call($method, $args)
    {
        if (method_exists($this->imagick, $method)) {
            return call_user_func_array([$this->imagick, $method], $args);
        }
        throw new BadMethodCallException("No method by that name");
    }

    public function compositeColor($color, $mode = 'normal')
    {
        $mode = self::$composites[$mode];
        $layer = new Imagick();
        $layer->newImage($this->imagick->getImageWidth(), $this->imagick->getImageHeight(), new ImagickPixel($color));
        $layer->setImageFormat('jpg');
        $this->imagick->compositeImage($layer, $mode, 0, 0);
        return $this;
    }

    public function baseCurve($channel, $value, $output = false)
    {
        $expression = 'u+';
        $channel = self::$channels[$channel];
        if ($output == true) {
            $expression .= '(' . (string) $value . '/255)';
        } else {
            $expression .= '(u*' . (string) $value . '/255)';
        }
        $this->imagick = $this->imagick->fxImage($expression, $channel);
        return $this;
    }

    public function levels($black, $gamma, $white)
    {
        $quantumRangeLong = $image->getQuantum();
        $this->imagick->levelImage(
            $black*$quantumRangeLong/255,
            $gamma,
            $white*$quantumRangeLong/255,
            Imagick::CHANNEL_ALL
        );
        return $this;
    }

    public function colortone($color, $level, $type = 0)
    {
        $this->imagick->setImageColorspace(Imagick::COLORSPACE_RGB);
        $args = [$level, 100-$level];
        $negate = $type == 0 ? true : false;

        $opacityColor = new ImagickPixel("rgba(0, 0, 0, 100)");
        $layer_1 = clone $this->imagick;
        $layer_1->colorizeImage($color, $opacityColor);

        $layer_2 = clone $this->imagick;
        $layer_2->setImageColorspace(Imagick::COLORSPACE_GRAY);
        if ($negate) {
            $layer_2->negateImage(0);
        }

        $this->imagick->setOption('compose:args', $args[0] . ',' . $args[1]);

        $this->imagick->compositeImage($layer_1, Imagick::COMPOSITE_BLEND, 0, 0);
        $this->imagick->compositeImage($layer_2, Imagick::COMPOSITE_BLEND, 0, 0);
    }

    public function customVignette($color_1 = 'none', $color_2 = 'black', $crop_factor = 1.5)
    {
        $dim = $this->imagick->getImageGeometry();
        $crop_x = floor($dim['width'] * $crop_factor);
        $crop_y = floor($dim['height'] * $crop_factor);
        
        $layer = new Imagick();
        $layer->newPseudoImage($crop_x, $crop_y, 'radial-gradient:'.$color_1.'-'.$color_2);

        $layer->cropThumbnailImage($dim['width'], $dim['height']);
        $layer->setImagePage($dim['width'], $dim['height'], 0, 0);

        $this->imagick->compositeImage($layer_1, Imagick::COMPOSITE_MULTIPLY, 0, 0);
        $this->imagick->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);

        return $this;
    }
}
