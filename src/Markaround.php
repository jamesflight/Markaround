<?php

namespace Jamesflight\Markaround;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use DateTime;
use Jamesflight\Markaround\Exceptions\MarkdownFileNotFoundException;
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
     * @throws \Exception
     */
    public function where()
    {
        $args = func_get_args();

        list($field, $value, $operator) = $this->getWhereVars($args);

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
     * @return $this
     */
    public function orWhere()
    {
        $args = func_get_args();

        list($field, $value, $operator) = $this->getWhereVars($args);

        $queryCollection = $this->collection;

        $this->setCollection($this->path);

        $this->where($field, $operator, $value);

        $this->collection = $this->collection->merge($queryCollection);

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
     * @throws MarkdownFileNotFoundException
     */
    public function firstOrFail()
    {
        $first = $this->collection->first();
        $this->resetCollection();
        if (is_object($first)) {
            return $first;
        }
        throw new MarkdownFileNotFoundException('No markdown files were found.');
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
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        $this->resetCollection();
        $file = $this->where('id', $id)->first();
        $this->resetCollection();
        return $file;
    }

    /**
     * @param $id
     * @return mixed
     * @throws MarkdownFileNotFoundException
     */
    public function findOrFail($id)
    {
        $this->resetCollection();
        $file = $this->where('id', $id)->first();
        $this->resetCollection();
        if (is_object($file)) {
            return $file;
        }
        throw new MarkdownFileNotFoundException("Markdown file with id $id could not be found.");
    }

    /**
     * @return mixed
     */
    public function all()
    {
        $this->setCollection($this->path);
        $all = $this->collection;
        $this->resetCollection();
        return $all;
    }

    public function __call($method, $args)
    {
        if (Util::stringBeginsWith($method, 'where')) {
            $value = $args[0];
            return $this->whereMagic($method, $value);
        }
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
        $this->collection = $this->getFullCollection($path);
    }

    /**
     * @param $path
     * @return Collection
     */
    private function getFullCollection($path)
    {
        $paths = $this->filesystem->files($path);
        $collection = new Collection();
        foreach ($paths as $filepath) {
            $file = new MarkdownFile($this->markdownParser, $this->filesystem);
            $file->setPath($filepath);
            $collection->push($file);
        }
        return $collection;
    }

    /**
     * @param $args
     * @return array
     */
    private function getWhereVars($args)
    {
        $argNumber = count($args);
        $field = $args[0];

        switch ($argNumber) {
            case 3:
                $value = $args[2];
                $operator = $args[1];
                break;
            case 2:
                $value = $args[1];
                $operator = null;
                break;
            default:
                throw new \BadMethodCallException("Expected 2 or 3 arguments, got $argNumber");
                break;
        }
        return array($field, $value, $operator);
    }

    /**
     * @param $method
     * @param $value
     * @return $this
     */
    private function whereMagic($method, $value)
    {
        $property = strtolower(substr($method, 5));
        $this->where($property, $value);
        return $this;
    }
}
