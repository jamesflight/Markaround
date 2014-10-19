<?php
namespace Jamesflight\Markaround;

use DateTime;

class Util
{
    public static function getBasenameWithoutExtension($basename)
    {
        return explode(".", $basename)[0];
    }

    public static function isADate($potentialDate)
    {
        return DateTime::createFromFormat('Y-m-d', $potentialDate) ? true : false;
    }

    public static function stringContains($needle, $haystack)
    {
        return strpos($haystack, $needle) ? true : false;
    }
}
