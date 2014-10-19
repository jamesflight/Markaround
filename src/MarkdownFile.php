<?php

namespace Jamesflight\Markaround;

use DateTime;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

/**
 * Class MarkdownFile
 * @package Jamesflight\Markaround
 */
class MarkdownFile
{
    /**
     * @var
     */
    public $slug;

    /**
     * @var
     */
    public $date;

    /**
     * @var
     */
    public $basename;

    /**
     * @var
     */
    public $id;

    /**
     * @var
     */
    private $markdownParser;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var bool
     */
    private $fileHasBeenParsed = false;

    /**
     * @var bool
     */
    private $htmlHasBeenParsed = false;

    /**
     * @var
     */
    private $parsedHtml;

    /**
     * @var
     */
    private $markdown;

    /**
     * @var
     */
    private $customFields;

    /**
     * @var
     */
    private $yaml;

    /**
     * @param $markdownParser
     * @param Filesystem $filesystem
     */
    public function __construct($markdownParser, Filesystem $filesystem)
    {
        $this->markdownParser = $markdownParser;
        $this->filesystem = $filesystem;
    }

    /**
     * @param $path
     */
    public function setPath($path)
    {
        $this->path = $path;
        $this->basename = basename($path);
        $this->setPropertiesFromPath();
    }

    /**
     * @return mixed
     */
    public function getHtml()
    {
        $this->parseFileIfNeccessary();
        $this->parseHtmlIfNeccessary();
        return $this->parsedHtml;
    }

    /**
     * @param $field
     * @return mixed
     */
    public function getCustomField($field)
    {
        $this->parseFileIfNeccessary();
        if (isset($this->customFields[$field])) {
            return $this->customFields[$field];
        }
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if (in_array($name, ['html'])) {
            $method = 'get' . ucfirst($name);
            return $this->$method();
        } else {
            return $this->getCustomField($name);
        }
    }

    /**
     *
     */
    private function setPropertiesFromPath()
    {
        $fullSlug = Util::removeExtensionFromFilename($this->basename);

        // If there is an id in the path, set it on the object
        if (Util::stringContains('_', $fullSlug)) {
            $idExplodedSlug =  explode('_', $fullSlug);
            $this->id = (integer) $idExplodedSlug[0];
            $fullSlug = $idExplodedSlug[1];
        }

        $potentialDate = substr($fullSlug, 0, 10);
        // If there is an date in the path, set it on the object, and set the remaining string as the slug
        if (Util::isADate($potentialDate)) {
            $this->date = substr($fullSlug, 0, 10);
            $this->slug = substr($fullSlug, 11);
        } else {
            // If no date or id, set the slug
            $this->slug = $fullSlug;
        }
    }

    /**
     * @throws \Illuminate\Filesystem\FileNotFoundException
     */
    private function parseFile()
    {
        $fileContents = $this->filesystem->get($this->path);

        if (substr_count($fileContents, '---') >= 2) {
            $exploded = explode('---', $fileContents);
            $this->yaml = $exploded[1];
            $this->markdown = $exploded[2];
        } else {
            $this->markdown = $fileContents;
        }

        $this->parseYaml();
    }

    /**
     *
     */
    private function parseYaml()
    {

        $data = Yaml::parse($this->yaml);

        if (is_array($data)) {
            foreach ($data as $field => $value) {
                $this->customFields[$field] = $value;
            }
        }
    }

    /**
     *
     */
    private function parseHtml()
    {
        $this->parsedHtml = $this->markdownParser->text($this->markdown);
    }


    /**
     *
     */
    private function parseFileIfNeccessary()
    {
        if (!$this->fileHasBeenParsed) {
            $this->parseFile();
            $this->fileHasBeenParsed = true;
        }
    }

    /**
     *
     */
    private function parseHtmlIfNeccessary()
    {
        if (!$this->htmlHasBeenParsed) {
            $this->parseHtml();
            $this->htmlHasBeenParsed = true;
        }
    }
}
