<?php
namespace Jamesflight\Markaround;

use DateTime;

/**
 * Class Util
 * @package Jamesflight\Markaround
 */
class Util
{
    /**
     * @param $basename
     * @return mixed
     */
    public static function removeExtensionFromFilename($basename)
    {
        return explode(".", $basename)[0];
    }

    /**
     * @param $potentialDate
     * @return bool
     */
    public static function isADate($potentialDate)
    {
        return DateTime::createFromFormat('Y-m-d', $potentialDate) ? true : false;
    }

    /**
     * @param $needle
     * @param $haystack
     * @return bool
     */
    public static function stringContains($needle, $haystack)
    {
        return strpos($haystack, $needle) ? true : false;
    }
}
