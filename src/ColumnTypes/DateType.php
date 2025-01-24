<?php

namespace ObadaAz\AutoCrud\ColumnTypes;

class DateType extends ColumnType
{
    public function validationRules(): array
    {
        return ['required', 'date'];
    }
}