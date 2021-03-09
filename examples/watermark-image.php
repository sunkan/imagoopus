<?php declare(strict_types=1);

use ImagoOpus\Actions\Chain;
use ImagoOpus\Actions\Resize;
use ImagoOpus\Actions\Watermark;
use ImagoOpus\Image;
use ImagoOpus\Values\Gravity;
use ImagoOpus\Values\Text;

require '../vendor/autoload.php';

$image = Image::fromPath('resources/image1.jpg');
$watermark = Image::fromPath('resources/sample-watermark.png');

$gravity = new Gravity(Gravity::NORTH_WEST);

$image = $image->preform(new Chain(
    new Resize(1920, 1080),
    new Watermark($watermark, $gravity),
));
$image->writeImages('output/watermarked-image.jpg');
