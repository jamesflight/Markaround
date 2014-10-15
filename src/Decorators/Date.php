<?php
namespace Jamesflight\Markaround\Decorators;

use Jamesflight\Markaround\MarkdownFile;
use DateTime;

class Date
{
    public function compare(MarkdownFile $markdownFile, $value, $operator)
    {
        $fileDate = $this->getDateAsUnixTimestamp($markdownFile->date);
        $valueDate = $this->getDateAsUnixTimestamp($value);
        return $operator->compare($fileDate, $valueDate);
    }

    private function getDateAsUnixTimestamp($date)
    {
        return (new DateTime($date))->format('U');
    }
}
