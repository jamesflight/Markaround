<?php
namespace Jamesflight\Markaround;

use Jamesflight\Markaround\Operators\Equals;
use Jamesflight\Markaround\Decorators\Date;
use Jamesflight\Markaround\Operators\GreaterThan;
use Jamesflight\Markaround\Operators\GreaterThanOrEqualTo;
use Jamesflight\Markaround\Operators\LessThan;
use Jamesflight\Markaround\Operators\LessThanOrEqualTo;
use Jamesflight\Markaround\Operators\NotEquals;

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
    public static function create($config)
    {
        $markaround = new Markaround(
            new ComparisonProcessor(
                [
                    '=' => new Equals(),
                    '>' => new GreaterThan(),
                    '<' => new LessThan(),
                    '>=' => new GreaterThanOrEqualTo(),
                    '<=' => new LessThanOrEqualTo(),
                    '!=' => new NotEquals()
                ],
                [
                    'date' => new Date()
                ]
            )
        );

        $markaround->setConfig($config);

        return $markaround;
    }
}
