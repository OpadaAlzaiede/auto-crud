<?php

namespace ObadaAz\AutoCrud\ColumnTypes;

class DatetimeType extends ColumnType
{
    public function validationRules(): array
    {
        return ['required', 'datetime'];
    }
}