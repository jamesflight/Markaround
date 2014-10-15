<?php

namespace Jamesflight\Markaround\Operators;

class Equals
{
    public function compare($field, $value)
    {
        return $field === $value ? true : false;
    }
}
