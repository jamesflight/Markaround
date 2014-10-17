<?php

namespace Jamesflight\Markaround;

use DateTime;
use Illuminate\Filesystem\Filesystem;

class MarkdownFile
{
    public $slug;

    public $date;

    public $basename;

    public $id;

    private $parser;

    private $filesystem;

    private $fileHasBeenParsed = false;

    private $parsedHtml;

    public function __construct($parser, Filesystem $filesystem)
    {
        $this->parser = $parser;
        $this->filesystem = $filesystem;
    }

    public function setPath($path)
    {
        $this->path = $path;
        $this->basename = basename($path);
        $this->setPropertiesFromPath();
    }

    public function getHtml()
    {
        $this->parseFileIfNeccessary();
        return $this->parsedHtml;
    }

    public function __get($name)
    {
        $method = 'get' . ucfirst($name);
        return $this->$method();
    }

    private function setPropertiesFromPath()
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

    private function parseFile()
    {
        $fileContents = $this->filesystem->get($this->path);
        $this->parsedHtml = $this->parser->text($fileContents);
    }

    private function getBasenameWithoutExtension()
    {
        return explode(".", $this->basename)[0];
    }

    private function isADate($potentialDate)
    {
        return DateTime::createFromFormat('Y-m-d', $potentialDate) ? true : false;
    }

    private function stringContains($needle, $haystack)
    {
        return strpos($haystack, $needle) ? true : false;
    }

    private function parseFileIfNeccessary()
    {
        if (!$this->fileHasBeenParsed) {
            $this->parseFile();
            $this->fileHasBeenParsed = true;
        }
    }
}
