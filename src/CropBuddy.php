<?php
/**
 * Ryan's Crop Buddy
 *
 * @package Rych\CropBuddy
 * @author Ryan Chouinard <rchouinard@gmail.com>
 * @copyright Copyright (c) 2014, Ryan Chouinard
 * @license MIT License - http://www.opensource.org/licenses/mit-license.php
 */

namespace Rych\CropBuddy;

use Imagick;
use Rych\CropBuddy\Strategy\StrategyInterface;

class CropBuddy
{

    /**
     * @var \Imagick
     */
    protected $imagick;

    /**
     * @var \CropBuddy\Strategy\StrategyInterface
     */
    protected $strategy;

    /**
     * @param \Imagick $imagick
     * @param \CropBuddy\Strategy\StrategyInterface $strategy
     */
    public function __construct(Imagick $imagick, StrategyInterface $strategy = null)
    {
        if (!$strategy) {
            $strategy = new Strategy\CenterStrategy();
        }

        $this->imagick = $imagick;
        $this->strategy = $strategy;
    }

    /**
     * @param integer $width
     * @param integer $height
     * @return boolean
     */
    public function crop($width, $height)
    {
        return $this->strategy->crop($this->imagick, $width, $height);
    }

    /**
     * @param integer $width
     * @param integer $height
     * @return boolean
     */
    public function resize($width, $height)
    {
        $geometry = $this->imagick->getImageGeometry();
        if (($geometry["width"] / $geometry["height"]) < ($width / $height)) {
            $scale = $geometry["width"] / $width;
        } else {
            $scale = $geometry["height"] / $height;
        }

        $scaleWidth = (int) $geometry["width"] / $scale;
        $scaleHeight = (int) $geometry["height"] / $scale;

        return $this->imagick->resizeImage($scaleWidth, $scaleHeight, Imagick::FILTER_GAUSSIAN, 1);
    }

}
