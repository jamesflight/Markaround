<?php

namespace spec\Jamesflight\Markaround;

use Illuminate\Filesystem\Filesystem;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MarkdownFileSpec extends ObjectBehavior
{
    function let(\Parsedown $parser, Filesystem $filesystem)
    {
        $this->beConstructedWith($parser, $filesystem);
    }

    function it_can_be_created_with_a_path()
    {
        $this->setPath('a/path/with/a-slug.md');
        $this->path->shouldBe('a/path/with/a-slug.md');
    }

    function it_can_have_a_slug()
    {
        $this->setPath('a/path/with/a-slug.md');
        $this->slug->shouldBe("a-slug");
    }

    function it_can_have_a_date()
    {
        $this->setPath('a/path/with/2014-11-12-a-slug.md');
        $this->date->shouldBe("2014-11-12");
    }

    function it_can_have_an_id()
    {
        $this->setPath('path/to/05_a-slug.md');
        $this->id->shouldBe(5);
    }

    function it_can_parse_html_from_file(\Parsedown $parser, Filesystem $filesystem)
    {
        $this->setPath('path/to/file.md');

        $filesystem
            ->get('path/to/file.md')
            ->shouldBeCalled()
            ->willReturn('Some Markdown');

        $parser
            ->text('Some Markdown')
            ->shouldBeCalled()
            ->willReturn('Parsed Markdown');

        $this->getHtml()->shouldBe('Parsed Markdown');
    }

}
