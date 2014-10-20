<?php

namespace Jamesflight\Markaround\Operators;

class LessThan
{
    public function compare($field, $value)
    {
        return $field < $value ? true : false;
    }
}
