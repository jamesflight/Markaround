<?php
namespace Jamesflight\Markaround\Operators;

class NotEquals implements Operator
{
    public function compare($field, $value)
    {
        return $field !== $value;
    }
}
