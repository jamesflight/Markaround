<?php

namespace Jamesflight\Markaround;

use Jamesflight\Markaround\Operators\Equals;
use Jamesflight\Markaround\Decorators\Date;

class Factory
{
    public function create($config)
    {
        return Markaround::create(
            $config,
            [
                '=' => new Equals()
            ],
            [
                'date' => new Date()
            ]
        );
    }
}
