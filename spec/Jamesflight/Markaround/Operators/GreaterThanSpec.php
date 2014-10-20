<?php

namespace spec\Jamesflight\Markaround\Operators;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GreaterThanSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Jamesflight\Markaround\Operators\GreaterThan');
    }

    function it_can_compare_two_integer_strings()
    {
        $this->compare('05', '06')->shouldBe(false);
    }

    function it_can_compare_two_strings()
    {
        $this->compare('b', 'a')->shouldBe(true);
    }
}
