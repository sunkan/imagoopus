#ImagoOpus

Image manipulation library. 
Used on for.me, my.nelly.com, stylewish.me


##Example

Resize image
```php
<?php
$image = new ImagoOpus\Image('image.jpg');
$action = new ImagoOpus\Action\Resize([
  'width' => 100,
  'type' => 'square'
]);
$image->preform($action);
$image->writeImage();
```

Chain actions
```php
<?php
$image = new ImagoOpus\Image('image.jpg');
$chain = new ImagoOpus\Action\Chain();
$chain[] = new ImagoOpus\Action\Crop([
  'width' => 500,
  'height' => 250, 
  'x' => 50,
  'y' => 50
]);
$chain[] = new ImagoOpus\Action\Resize([
  'width' => 100
]);
$image->preform($chain);
$image->writeImage();
```