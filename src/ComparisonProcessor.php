<?php

namespace Jamesflight\Markaround;

class ComparisonProcessor
{
    private $operators;

    private $decorators;

    public function __construct($operators, $decorators)
    {
        $this->operators = $operators;
        $this->decorators = $decorators;
    }

    public function compare(MarkdownFile $markdownFile, $field, $value, $operator = null)
    {
        if ($operator === null) {
            $operator = $this->operators[key($this->operators)];
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
