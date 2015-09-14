<?php

namespace Jamesflight\Markaround\Operators;

class LessThanOrEqualTo implements Operator
{
    public function compare($field, $value)
    {
        return $field <= $value ? true : false;
    }
}
