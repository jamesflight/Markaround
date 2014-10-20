<?php

namespace Jamesflight\Markaround;

/**
 * Class ComparisonProcessor
 * @package Jamesflight\Markaround
 */
class ComparisonProcessor
{
    /**
     * @var
     */
    private $operators;

    /**
     * @var
     */
    private $decorators;

    /**
     * @param $operators
     * @param $decorators
     */
    public function __construct($operators, $decorators)
    {
        $this->operators = $operators;
        $this->decorators = $decorators;
    }

    /**
     * @param MarkdownFile $markdownFile
     * @param $field
     * @param $value
     * @param null $operator
     * @return bool
     */
    public function compare(MarkdownFile $markdownFile, $field, $value, $operator = null)
    {

        if ($operator === null) {
            $operator = array_values($this->operators)[0];
        } else {
            $operator = $this->operators[$operator];
        }

        if (array_key_exists($field, $this->decorators)) {
            if ($this->decorators[$field]->compare($markdownFile, $value, $operator)) {
                return true;
            }
        }

        if ($operator->compare($markdownFile->$field, $value)) {
            return true;
        }

        return false;
    }
}
