<?php
/**
 * Ryan's Crop Buddy
 *
 * @package Rych\CropBuddy
 * @author Ryan Chouinard <rchouinard@gmail.com>
 * @copyright Copyright (c) 2014, Ryan Chouinard
 * @license MIT License - http://www.opensource.org/licenses/mit-license.php
 */

namespace Rych\CropBuddy\Strategy;

use Imagick;

interface StrategyInterface
{

    /**
     * @param \Imagick $imagick
     * @param integer $width
     * @param integer $height
     * @return boolean
     */
    public function crop(Imagick $imagick, $width, $height = null);

}
