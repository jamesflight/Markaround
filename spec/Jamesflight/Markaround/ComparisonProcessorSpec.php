<?php

namespace spec\Jamesflight\Markaround;

use Jamesflight\Markaround\Decorators\Date;
use Jamesflight\Markaround\MarkdownFile;
use Jamesflight\Markaround\Operators\Equals;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ComparisonProcessorSpec extends ObjectBehavior
{
    function let(Equals $equals, Date $date)
    {
        $this->beConstructedWith(
            [
                '=' => $equals
            ],
            [
                'date' => $date
            ]);
    }

    function it_can_compare_a_value_with_a_field_from_a_markdown_file(MarkdownFile $markdownFile, Equals $equals)
    {
        $markdownFile->slug = 'a-slug';

        $equals
            ->compare('a-slug', 'a-slug')
            ->shouldBeCalled()
            ->willReturn(true);

        $field = 'slug';
        $value = 'a-slug';
        $operator = '=';

        $this
            ->compare($markdownFile, $field, $value, $operator)
            ->shouldBe(true);
    }

    function it_can_compare_a_value_with_a_field_that_requires_decorator_from_a_markdown_file(MarkdownFile $markdownFile, Date $date, Equals $equals)
    {
        $markdownFile->date = '2014-10-10';

        $date
            ->compare($markdownFile, '2014-10-09', $equals)
            ->shouldBeCalled()
            ->willReturn(false);

        $field = 'date';
        $value = '2014-10-09';
        $operator = '=';

        $this
            ->compare($markdownFile, $field, $value, $operator)
            ->shouldBe(false);
    }

    function it_can_compare_a_value_with_a_field_from_a_markdown_file_if_no_operator_specified(MarkdownFile $markdownFile, Equals $equals)
    {
        $markdownFile->slug = 'a-slug';

        $equals
            ->compare('a-slug', 'a-slug')
            ->shouldBeCalled()
            ->willReturn(true);

        $field = 'slug';
        $value = 'a-slug';

        $this
            ->compare($markdownFile, $field, $value)
            ->shouldBe(true);
    }

}
