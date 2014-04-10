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

class CenterStrategy implements StrategyInterface
{

    /**
     * @param \Imagick $imagick
     * @param integer $width
     * @param integer $height
     * @return boolean
     */
    public function crop(Imagick $imagick, $width, $height = null)
    {
        if ($height === null) {
            $height = $width;
        }

        $width = max(1, (int) $width);
        $height = max(1, (int) $height);

        $offset = self::getCropOffset($imagick, $width, $height);
        $imagick->cropImage($width, $height, $offset["x"], $offset["y"]);
    }

    /**
     * @param \Imagick $imagick
     * @param integer $width
     * @param integer $height
     * @return array
     */
    protected static function getCropOffset(Imagick $imagick, $width, $height)
    {
        $size = $imagick->getImageGeometry();
        $x = (int) (($size["width"] - $width) / 2);
        $y = (int) (($size["height"] - $height) / 2);

        return array ("x" => $x, "y" => $y);
    }

}
