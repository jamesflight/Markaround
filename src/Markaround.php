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

    private $filepaths;

    private $operators;

    private $decorators;

    public function __construct(Filesystem $filesystem, Collection $collection, $operators, $decorators)
    {
        $this->filesystem = $filesystem;
        $this->collection = $collection;
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
        return $this->collection->first();
    }

    public function get()
    {
        return $this->collection;
    }

    public function setConfig($config)
    {
        $this->path = $config['default_path'];
        $this->filepaths = $this->filesystem->files($this->path);
        $this->setCollection();
    }

    public static function create($config, $operators, $fields)
    {
        $object = new static(new Filesystem(), new Collection(), $operators, $fields);
        $object->setConfig($config);
        return $object;
    }

    private function setCollection()
    {
        foreach ($this->filepaths as $filepath) {
            $this->collection->push(new MarkdownFile($filepath));
        }
    }
}
