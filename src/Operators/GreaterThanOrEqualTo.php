<?php

namespace Jamesflight\Markaround\Operators;

class GreaterThanOrEqualTo
{
    public function compare($field, $value)
    {
        return $field >= $value ? true : false;
    }
}
