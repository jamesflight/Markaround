<?php
namespace Jamesflight\Markaround\Decorators;

use Jamesflight\Markaround\MarkdownFile;
use DateTime;

/**
 * Class Date
 * @package Jamesflight\Markaround\Decorators
 */
class Date implements Decorator
{
    /**
     * @param MarkdownFile $markdownFile
     * @param $value
     * @param $operator
     * @return mixed
     */
    public function compare(MarkdownFile $markdownFile, $value, $operator)
    {
        if (! $markdownFile->date) {
            return false;
        }

        $fileDate = $this->getDateAsUnixTimestamp($markdownFile->date);
        $valueDate = $this->getDateAsUnixTimestamp($value);
        return $operator->compare($fileDate, $valueDate);
    }

    /**
     * @param $date
     * @return string
     */
    private function getDateAsUnixTimestamp($date)
    {
        return (new DateTime($date))->format('U');
    }
}
