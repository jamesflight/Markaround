<?php

namespace Jamesflight\Markaround\Operators;

class GreaterThan
{
    public function compare($field, $value)
    {
        return $field > $value ? true : false;
    }
}
