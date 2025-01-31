<?php

namespace ObadaAz\AutoCrud\ColumnTypes;

abstract class ColumnType
{
    /**
     * @return array
     */
    abstract public function validationRules(): array;

    /**
     * @return string
     */
    public function getValidationRule(): string
    {
        return implode('|', $this->validationRules());
    }
}
