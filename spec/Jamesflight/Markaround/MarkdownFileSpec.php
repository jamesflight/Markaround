<?php

namespace spec\Jamesflight\Markaround;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MarkdownFileSpec extends ObjectBehavior
{

    function it_can_be_created_with_a_path()
    {
        $this->beConstructedWith('a/path/with/a-slug.md');
        $this->path->shouldBe('a/path/with/a-slug.md');
    }

    function it_can_have_a_slug()
    {
        $this->beConstructedWith('a/path/with/a-slug.md');
        $this->slug->shouldBe("a-slug");
    }

    function it_can_have_a_date()
    {
        $this->beConstructedWith('a/path/with/2014-11-12_a-slug.md');
        $this->date->shouldBe("2014-11-12");
    }
}
