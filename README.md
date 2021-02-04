# ImagoOpus

Image manipulation library. 

## Example

Resize image

```php
<?php
use ImagoOpus\Actions\Resize;
use ImagoOpus\Image;

$image = Image::fromPath('image.jpg');
$action = new Resize(100, 0, Resize::TYPE_SQUARE);
$image->preform($action);
$image->getImagesBlob();
```

Chain actions

```php
<?php
use ImagoOpus\Actions\Chain;
use ImagoOpus\Actions\Crop;
use ImagoOpus\Actions\Resize;
use ImagoOpus\Image;

$image = Image::fromPath('image.jpg');
$chain = new Chain();
$chain[] = new Crop(500, 250, 50, 50);
$chain[] = new Resize(100, 0);
$image->preform($chain);
$image->getImagesBlob();
```
