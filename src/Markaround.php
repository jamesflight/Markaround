<?php

namespace Jamesflight\Markaround;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use DateTime;

class Markaround
{
    private $path;

    private $filesystem;

    private $collection;

    private $operators;

    private $decorators;

    public function __construct(Filesystem $filesystem, $operators, $decorators)
    {
        $this->filesystem = $filesystem;
        $this->collection = new Collection();
        $this->operators = $operators;
        $this->decorators = $decorators;
    }

    public function where()
    {
        $field = func_get_arg(0);

        if (array_key_exists(func_get_arg(1), $this->operators)) {
            $value = func_get_arg(2);
            $operator = $this->operators[func_get_arg(1)];
        } else {
            $value = func_get_arg(1);
            $operator = $this->operators[key($this->operators)];
        }

        $newCollection = new Collection();

        foreach ($this->collection as $file) {
            if (array_key_exists($field, $this->decorators)) {
                if ($this->decorators[$field]->compare($file, $value, $operator)) {
                    $newCollection->push($file);
                }
            } else {
                if ($operator->compare($file->$field, $value)) {
                    $newCollection->push($file);
                }
            }

        }

        $this->collection = $newCollection;

        return $this;
    }

    public function first()
    {
        $collection = $this->collection;
        $this->resetCollection();
        return $collection->first();
    }

    public function get()
    {
        $collection = $this->collection;
        $this->resetCollection();
        return $collection;
    }

    public function in($path)
    {
        $this->setCollection($path);
        return $this;
    }

    public function setConfig($config)
    {
        $this->path = $config['default_path'];
        $this->setCollection($this->path);
    }

    private function resetCollection()
    {
        $this->setCollection($this->path);
    }

    private function setCollection($path)
    {
        $paths = $this->filesystem->files($path);
        $this->collection = new Collection();
        foreach ($paths as $filepath) {
            $this->collection->push(new MarkdownFile($filepath));
        }
    }
}
