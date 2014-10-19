<?php

namespace Jamesflight\Markaround\Operators;

/**
 * Class Equals
 * @package Jamesflight\Markaround\Operators
 */
class Equals
{
    /**
     * @param $field
     * @param $value
     * @return bool
     */
    public function compare($field, $value)
    {
        return $field === $value ? true : false;
    }
}
