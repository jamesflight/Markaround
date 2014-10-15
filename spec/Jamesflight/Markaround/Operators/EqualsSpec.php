<?php

namespace spec\Jamesflight\Markaround\Operators;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EqualsSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Jamesflight\Markaround\Operators\Equals');
    }

    function it_returns_ture_for_same_field_and_value()
    {
        $field = 1;
        $value = 1;
        $this
            ->compare($field, $value)
            ->shouldBe(true);
    }

    function it_returns_false_when_field_and_value_not_same()
    {
        $field = '3';
        $value = 9;
        $this
            ->compare($field, $value)
            ->shouldBe(false);
    }
}
