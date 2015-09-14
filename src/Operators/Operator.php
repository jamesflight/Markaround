<?php
namespace Jamesflight\Markaround\Operators;

interface Operator
{
    public function compare($field, $value);
}
