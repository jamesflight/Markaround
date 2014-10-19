<?php

namespace Jamesflight\Markaround;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use DateTime;
use Parsedown;
use Symfony\Component\Yaml\Yaml;

/**
 * Class Markaround
 * @package Jamesflight\Markaround
 */
class Markaround
{
    /**
     * @var
     */
    private $path;

    /**
     * @var Illuminate\Filesystem\Filesystem;
     */
    private $filesystem;

    /**
     * @var
     */
    private $collection;

    /**
     * @var Parsedown
     */
    private $markdownParser;

    /**
     * @var ComparisonProcessor
     */
    private $comparisonProcessor;

    /**
     * @param ComparisonProcessor $comparisonProcessor
     * @param null $markdownParser
     */
    public function __construct(ComparisonProcessor $comparisonProcessor, $markdownParser = null)
    {
        $this->markdownParser = $markdownParser ?: new Parsedown();
        $this->filesystem = new Filesystem();
        $this->comparisonProcessor = $comparisonProcessor;
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function where()
    {
        $field = func_get_arg(0);

        if (count(func_get_args()) === 3) {
            $value = func_get_arg(2);
            $operator = func_get_arg(1);
        } elseif (count(func_get_args()) === 2) {
            $value = func_get_arg(1);
            $operator = null;
        } else {
            throw new Exception('Incorrect number of arguments.');
        }

        $newCollection = new Collection();

        foreach ($this->collection as $file) {
            if ($this->comparisonProcessor->compare($file, $field, $value, $operator)) {
                $newCollection->push($file);
            }
        }

        $this->collection = $newCollection;

        return $this;
    }

    /**
     * @return mixed
     */
    public function first()
    {
        $collection = $this->collection;
        $this->resetCollection();
        return $collection->first();
    }

    /**
     * @return mixed
     */
    public function get()
    {
        $collection = $this->collection;
        $this->resetCollection();
        return $collection;
    }

    /**
     * @param $path
     * @return $this
     */
    public function in($path)
    {
        $this->setCollection($path);
        return $this;
    }

    /**
     * @param $config
     */
    public function setConfig($config)
    {
        $this->path = $config['default_path'];
        $this->setCollection($this->path);
    }

    /**
     *
     */
    private function resetCollection()
    {
        $this->setCollection($this->path);
    }

    /**
     * @param $path
     */
    private function setCollection($path)
    {
        $paths = $this->filesystem->files($path);
        $this->collection = new Collection();
        foreach ($paths as $filepath) {
            $file = new MarkdownFile($this->markdownParser, $this->filesystem);
            $file->setPath($filepath);
            $this->collection->push($file);
        }
    }
}
