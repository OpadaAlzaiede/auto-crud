<?php

namespace ObadaAz\AutoCrud\ColumnTypes;

abstract class ColumnType
{
    abstract public function validationRules(): array;

    public function getValidationRule(): string
    {
        return implode('|', $this->validationRules());
    }
}
