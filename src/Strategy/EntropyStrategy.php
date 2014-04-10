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

class EntropyStrategy implements StrategyInterface
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
        return $imagick->cropImage($width, $height, $offset["x"], $offset["y"]);
    }

    /**
     * @param \Imagick $imagick
     * @param integer $width
     * @param integer $height
     */
    protected static function getCropOffset(Imagick $imagick, $width, $height)
    {
        $imagick = self::getGrayscaleClone($imagick);
        $imagick->blurImage(3, 2);

        $geometry = $imagick->getImageGeometry();

        $originalWidth = $rightX = $geometry["width"];
        $originalHeight = $bottomY = $geometry["height"];
        $leftX = $topY = 0;

        $sliceSize = ceil(($originalWidth - $width) / 25);
        $leftSlice = $rightSlice = null;

        while($rightX - $leftX > $width) {
            $sliceSize = min($rightX - $leftX - $width, $sliceSize);

            if (!$leftSlice) {
                $leftSlice = clone $imagick;
                $leftSlice->cropImage($sliceSize, $originalHeight, $leftX, 0);
            }

            if (!$rightSlice) {
                $rightSlice = clone $imagick;
                $rightSlice->cropImage($sliceSize, $originalHeight, $rightX - $sliceSize, 0);
            }

            if (self::getEntropy($leftSlice) < self::getEntropy($rightSlice)) {
                $leftX += $sliceSize;
                $leftSlice = null;
            } else {
                $rightX -= $sliceSize;
                $rightSlice = null;
            }
        }

        $sliceSize = ceil(($originalHeight - $height) / 25);
        $topSlice = $bottomSlice = null;

        while($bottomY - $topY > $height) {
            $sliceSize = min($bottomY - $topY - $height, $sliceSize);

            if (!$topSlice) {
                $topSlice = clone $imagick;
                $topSlice->cropImage($originalWidth, $sliceSize, 0, $topY);
            }

            if (!$bottomSlice) {
                $bottomSlice = clone $imagick;
                $bottomSlice->cropImage($originalWidth, $sliceSize, 0, $bottomY - $sliceSize);
            }

            if (self::getEntropy($topSlice) < self::getEntropy($bottomSlice)) {
                $topY += $sliceSize;
                $topSlice = null;
            } else {
                $bottomY -= $sliceSize;
                $bottomSlice = null;
            }
        }

        return array ("x" => $leftX, "y" => $topY);
    }

    /**
     * @param \Imagick $imagick
     * @return float
     */
    protected static function getEntropy(Imagick $imagick)
    {
        $histogram = $imagick->getImageHistogram();
        $geometry = $imagick->getImageGeometry();
        $area = $geometry["width"] * $geometry["height"];

        $entropy = 0.0;
        /* @var $pixel \ImagickPixel */
        foreach ($histogram as $pixel) {
            $pixelPercentage = $pixel->getColorCount() / $area;
            $entropy += $pixelPercentage * log($pixelPercentage, 2);
        }

        return abs($entropy);
    }

    /**
     * @param \Imagick $imagick
     * @return \Imagick
     */
    protected static function getGrayscaleClone(Imagick $imagick)
    {
        $gray = clone $imagick;
        $gray->edgeimage(1);
        $gray->modulateImage(100, 0, 100);
        $gray->blackThresholdImage("#070707");

        return $gray;
    }

}
