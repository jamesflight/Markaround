<?php

namespace Jamesflight\Markaround\Operators;

class GreaterThanOrEqualTo implements Operator
{
    public function compare($field, $value)
    {
        return $field >= $value ? true : false;
    }
}
