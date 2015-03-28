<?php
namespace Jamesflight\Markaround;

use Jamesflight\Markaround\Operators\Equals;
use Jamesflight\Markaround\Decorators\Date;

/**
 * Class Factory
 * @package Jamesflight\Markaround
 */
class Factory
{
    /**
     * @param $config
     * @return mixed
     */
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
