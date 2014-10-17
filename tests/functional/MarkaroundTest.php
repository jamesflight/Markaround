<?php
use Illuminate\Filesystem\Filesystem;
use Jamesflight\Markaround\Decorators\Date;
use Jamesflight\Markaround\Factory;
use Jamesflight\Markaround\Markaround;
use Jamesflight\Markaround\Operators\Equals;

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
            new Filesystem(),
            [
                '=' => new Equals()
            ],
            [
                'date' => new Date()
            ],
            $this->parser
        );

        $this->markaround->setConfig([
            'default_path' => 'tests/stubs'
        ]);
    }

    public function test_can_query_by_slug()
    {
        $result = $this->markaround
                    ->where('slug', 'a-file-called-wanda')
                    ->first();

        $this->assertEquals('a-file-called-wanda', $result->slug);
    }

    public function test_can_query_by_date_in_different_format()
    {
        $result = $this->markaround
            ->where('date', '9th October 2014')
            ->first();

        $this->assertEquals('date-query-slug', $result->slug);
    }

    public function test_can_query_by_date()
    {
        $result = $this->markaround
                    ->where('date', '2014-10-09')
                    ->first();

        $this->assertEquals('date-query-slug', $result->slug);
    }

    public function test_can_chain_where_queries()
    {
        $result = $this->markaround
            ->where('date', '2014-10-09')
            ->where('slug', 'second-article-today')
            ->first();

        $this->assertEquals('second-article-today', $result->slug);
    }

    public function test_can_get_multiple_results_for_a_query()
    {
        $results = $this->markaround
            ->where('date', '2014-10-09')
            ->get();

        $this->assertEquals('date-query-slug', $results[0]->slug);
        $this->assertEquals('second-article-today', $results[1]->slug);
    }

    public function test_can_query_in_an_alternate_directory()
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

    public function test_can_use_equals_sign_to_query()
    {
        $result = $this->markaround
            ->where('date', '=', '2014-10-09')
            ->first();

        $this->assertEquals('date-query-slug', $result->slug);
    }

    public function test_can_query_by_id()
    {
        $result = $this->markaround
            ->where('id', 5)
            ->first();

        $this->assertEquals('file-with-id-slug', $result->slug);
    }

    public function test_query_returns_object_with_html()
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

    public function test_query_returns_object_with_custom_fields()
    {

    }

}