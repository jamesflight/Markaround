<?php

namespace Jamesflight\Markaround\Operators;

class LessThan implements Operator
{
    public function compare($field, $value)
    {
        return $field < $value ? true : false;
    }
}
