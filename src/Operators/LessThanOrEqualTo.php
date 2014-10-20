<?php

namespace Jamesflight\Markaround\Operators;

class LessThanOrEqualTo
{
    public function compare($field, $value)
    {
        return $field <= $value ? true : false;
    }
}
