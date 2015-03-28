<?php

use Jamesflight\Markaround\ComparisonProcessor;
use Jamesflight\Markaround\Decorators\Date;
use Jamesflight\Markaround\Markaround;
use Jamesflight\Markaround\Operators\Equals;
use Jamesflight\Markaround\Operators\GreaterThan;
use Jamesflight\Markaround\Operators\GreaterThanOrEqualTo;
use Jamesflight\Markaround\Operators\LessThan;
use Jamesflight\Markaround\Operators\LessThanOrEqualTo;

class MarkaroundTest extends \Codeception\TestCase\Test
{
   /**
    * @var \FunctionalTester
    */
    protected $tester;

    protected function _before()
    {
        $this->parser = Mockery::mock('Parsedown');
        $this->markaround = new Markaround(
            new ComparisonProcessor(
                [
                    '=' => new Equals(),
                    '>' => new GreaterThan(),
                    '<' => new LessThan(),
                    '>=' => new GreaterThanOrEqualTo(),
                    '<=' => new LessThanOrEqualTo()
                ],
                [
                    'date' => new Date()
                ]
            ),
            $this->parser
        );

        $this->markaround->setConfig([
            'default_path' => 'tests/stubs'
        ]);
    }

    function test_can_query_by_slug()
    {
        $result = $this->markaround
                    ->where('slug', 'a-file-called-wanda')
                    ->first();

        $this->assertEquals('a-file-called-wanda', $result->slug);
    }

    function test_can_query_by_date_in_different_format()
    {
        $result = $this->markaround
            ->where('date', '9th October 2014')
            ->first();

        $this->assertEquals('date-query-slug', $result->slug);
    }

    function test_can_query_by_date()
    {
        $result = $this->markaround
                    ->where('date', '2014-10-09')
                    ->first();

        $this->assertEquals('date-query-slug', $result->slug);
    }

    function test_can_chain_where_queries()
    {
        $result = $this->markaround
            ->where('date', '2014-10-09')
            ->where('slug', 'second-article-today')
            ->first();

        $this->assertEquals('second-article-today', $result->slug);
    }

    function test_can_get_multiple_results_for_a_query()
    {
        $results = $this->markaround
            ->where('date', '2014-10-09')
            ->get();

        $this->assertEquals('date-query-slug', $results[0]->slug);
        $this->assertEquals('second-article-today', $results[1]->slug);
    }

    function test_can_query_in_an_alternate_directory()
    {
        $result = $this->markaround
            ->in('tests/stubs/sub_directory')
            ->first();

        $this->assertEquals('sub-dir-file', $result->slug);

        // Check that performing an 'inDirectory' query doesn't break the next query
        $result = $this->markaround
            ->where('slug', 'a-file-called-wanda')
            ->first();

        $this->assertEquals('a-file-called-wanda', $result->slug);
    }

    function test_can_use_equals_sign_to_query()
    {
        $result = $this->markaround
            ->where('date', '=', '2014-10-09')
            ->first();

        $this->assertEquals('date-query-slug', $result->slug);
    }

    function test_can_query_by_id()
    {
        $result = $this->markaround
            ->where('id', 5)
            ->first();

        $this->assertEquals('file-with-id-slug', $result->slug);
    }

    function test_can_query_by_custom_field()
    {
        $result = $this->markaround
            ->where('foofield', 'foo')
            ->first();

        $this->assertEquals('file-with-custom-fields', $result->slug);
    }

    function test_query_returns_object_with_html()
    {
        $this->parser
            ->shouldReceive('text')
            ->once()
            ->with('Content')
            ->andReturn('Parsed Content');

        $result = $this->markaround
            ->where('slug', 'a-file-called-wanda')
            ->first();

        $this->assertEquals('Parsed Content', $result->html);
    }

    function test_query_returns_object_with_custom_fields()
    {
        $this->parser
            ->shouldReceive('text')
            ->once()
            ->with('Content')
            ->andReturn('Parsed Content');

        $result = $this->markaround
            ->where('slug', 'file-with-custom-fields')
            ->first();

        $this->assertEquals('foo', $result->foofield);
        $this->assertEquals('bar', $result->barfield);
    }

    function test_can_use_greater_than_sign_to_query()
    {
        $result = $this->markaround
            ->where('id', '>', '4')
            ->first();

        $this->assertEquals('file-with-id-slug', $result->slug);
    }

    function test_can_use_less_than_sign_to_query()
    {
        $result = $this->markaround
            ->where('id', '<', '6')
            ->get();

        $this->assertEquals('file-with-id-4', $result[0]->slug);
        $this->assertEquals('file-with-id-slug', $result[1]->slug);
    }

    function test_can_use_greater_than_or_equal_to_sign_in_query()
    {
        $results = $this->markaround
            ->where('id', '>=', '4')
            ->get();

        $this->assertEquals('file-with-id-4', $results[0]->slug);
        $this->assertEquals('file-with-id-slug', $results[1]->slug);
    }

    function test_can_use_less_than_or_equal_to_sign_in_query()
    {
        $results = $this->markaround
            ->where('id', '<=', '5')
            ->get();

        $this->assertEquals('file-with-id-slug', $results[1]->slug);
        $this->assertEquals('file-with-id-4', $results[0]->slug);
    }

    /**
     * @expectedException BadMethodCallException
     */
    function test_where_throws_exception_if_wrong_no_of_args_provided()
    {
        $results = $this->markaround
            ->where('id', '>=', '4', 'dafs')
            ->get();
    }

    function test_can_find_by_id()
    {
        $result = $this->markaround
            ->find(4);

        $this->assertEquals('file-with-id-4', $result->slug);
    }

    function test_find_or_fail_can_find_file()
    {
        $result = $this->markaround
            ->findOrFail(4);

        $this->assertEquals('file-with-id-4', $result->slug);
    }

    /**
     * @expectedException Jamesflight\Markaround\Exceptions\MarkdownFileNotFoundException
     */
    function test_find_or_fail_throws_exception_if_file_does_not_exist()
    {
        $result = $this->markaround
            ->findOrFail(101);
    }

    /**
     * @expectedException Jamesflight\Markaround\Exceptions\MarkdownFileNotFoundException
     */
    function test_first_or_fails_throws_exception_if_file_doesnt_exists()
    {
        $this->markaround
            ->where('id', '102')
            ->firstOrFail();
    }

    function test_can_get_all_files()
    {
        $results = $this->markaround->all();

        $this->assertEquals(6, $results->count());
    }

    function test_or_where()
    {
        $results = $this->markaround
            ->where('id', 4)
            ->orWhere('id', 5)
            ->get();

        $this->assertEquals(2, $results->count());
    }

    function test_magic_where()
    {
        $result = $this->markaround
            ->whereId(4)
            ->first();

        $this->assertEquals('file-with-id-4', $result->slug);

        $result = $this->markaround
            ->whereFoofield('foo')
            ->first();

        $this->assertEquals('file-with-custom-fields', $result->slug);
    }
}