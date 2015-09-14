<?php

namespace Jamesflight\Markaround\Operators;

class GreaterThan implements Operator
{
    public function compare($field, $value)
    {
        return $field > $value ? true : false;
    }
}
