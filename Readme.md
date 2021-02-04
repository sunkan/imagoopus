# ImagoOpus

Image manipulation library. 

## Example

Resize image
```php
<?php
$image = new ImagoOpus\Image('image.jpg');
$action = new ImagoOpus\Actions\Resize([
  'width' => 100,
  'type' => 'square'
]);
$image->preform($action);
$image->writeImages();
```

Chain actions
```php
<?php
$image = new ImagoOpus\Image('image.jpg');
$chain = new ImagoOpus\Actions\Chain();
$chain[] = new ImagoOpus\Actions\Crop([
  'width' => 500,
  'height' => 250, 
  'x' => 50,
  'y' => 50
]);
$chain[] = new ImagoOpus\Actions\Resize([
  'width' => 100
]);
$image->preform($chain);
$image->writeImages();
```
