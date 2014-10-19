<?php

namespace Jamesflight\Markaround;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Jamesflight\Markaround\Operators\Equals;
use Jamesflight\Markaround\Decorators\Date;

class Factory
{
    public function create($config)
    {
        $this->markaround = new Markaround(
            new ComparisonProcessor(
                [
                    '=' => new Equals()
                ],
                [
                    'date' => new Date()
                ]
            )
        );

        $object->setConfig($config);

        return $object;
    }
}
