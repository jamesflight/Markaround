<?php

namespace spec\Jamesflight\Markaround\Operators;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LessThanSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Jamesflight\Markaround\Operators\LessThan');
    }

    function it_can_compare_two_integer_strings()
    {
        $this->compare('05', '06')->shouldBe(true);
    }

    function it_can_compare_twp_strings()
    {
        $this->compare('b', 'a')->shouldBe(false);
    }
}
