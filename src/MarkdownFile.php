<?php

namespace Jamesflight\Markaround;

use DateTime;

class MarkdownFile
{
    public $slug;

    public $date;

    public $basename;

    public function __construct($path)
    {
        $this->path = $path;
        $this->basename = basename($path);
        $this->setDateAndASlug();
    }

    protected function setDateAndASlug()
    {
        $potentialDate = substr($this->basename, 0, 10);
        if (DateTime::createFromFormat('Y-m-d', $potentialDate)) {
            $this->date = substr($this->basename, 0, 10);
            $this->slug = explode(".", substr($this->basename, 11))[0];
        } else {
            $this->slug = explode(".", $this->basename)[0];
        }
    }
}
