<?php declare(strict_types=1);

use ImagoOpus\Actions\Chain;
use ImagoOpus\Actions\Resize;
use ImagoOpus\Actions\Watermark;
use ImagoOpus\Image;
use ImagoOpus\Values\Gravity;
use ImagoOpus\Values\Text;

require '../vendor/autoload.php';

$image = Image::fromPath('resources/image1.jpg');

$gravity = new Gravity(Gravity::SOUTH_EAST);

$text = new Text(
    'Watermark test',
    22,
    new ImagickPixel('#ffffff'),
    $gravity
);
$watermark = Image::createWithText($text);

$watermark->writeImages('output/test.png');

$image = $image->preform(new Chain(
    new Resize(1920, 1080),
    new Watermark($watermark, $gravity),
));
$image->writeImages('output/watermarked-text.jpg');
