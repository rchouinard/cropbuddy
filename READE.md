CropBuddy
=========

Another quick project written as part of a larger one. This library takes an
Imagick object and crops the image using a given cropping strategy.


How does it work?
-----------------

CropBuddy modifies your Imagick objects in place. It can resize and/or
crop images to given dimensions.

Included strategies are:

* CenterStrategy - the default, just ccrops from image center
* EntropyStrategy - crops to the most interesting part of an image (I
  make no promises as to how interesting the chosen bit really is)


Usage
-----

```php
<?php

$strategy = new \Rych\CropBuddy\Strategy\EntropyStrategy();
$cb = new \Rych\CropBuddy\CropBuddy($myImagickObject, $strategy);

// Resize the image to either a height or width of 150px
// A 600x300 image will end up at 300x150
$cb->resize(150, 150);

// Square the image to 150x150
$cb->crop(150, 150);
```


Installation
------------

Composer:

```bash
composer require rych/cropbuddy:dev-master@dev
```


To-do
-----

Add more strategies and write unit tests.
