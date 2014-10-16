<?php

namespace Jamesflight\Markaround;

use DateTime;

class MarkdownFile
{
    public $slug;

    public $date;

    public $basename;

    public $id;

    public function __construct($path)
    {
        $this->path = $path;
        $this->basename = basename($path);
        $this->setPropertiesFromPath();
    }

    protected function setPropertiesFromPath()
    {
        $fullSlug = $this->getBasenameWithoutExtension();
        // If there is an id in the path, set it on the object
        if ($this->stringContains('_', $fullSlug)) {
            $idExplodedSlug =  explode('_', $fullSlug);
            $this->id = (integer) $idExplodedSlug[0];
            $fullSlug = $idExplodedSlug[1];
        }

        $potentialDate = substr($fullSlug, 0, 10);
        // If there is an date in the path, set it on the object, and set the remaining string as the slug
        if ($this->isADate($potentialDate)) {
            $this->date = substr($fullSlug, 0, 10);
            $this->slug = substr($fullSlug, 11);
        } else {
            // If no date or id, set the slug
            $this->slug = $fullSlug;
        }
    }

    protected function getBasenameWithoutExtension()
    {
        return explode(".", $this->basename)[0];
    }

    protected function isADate($potentialDate)
    {
        return DateTime::createFromFormat('Y-m-d', $potentialDate) ? true : false;
    }

    protected function stringContains($needle, $haystack)
    {
        return strpos($haystack, $needle) ? true : false;
    }
}
