<?php
namespace Jamesflight\Markaround\Decorators;

use Jamesflight\Markaround\MarkdownFile;

interface Decorator
{
    public function compare(MarkdownFile $file, $value, $operator);
}
