<?php

namespace ObadaAz\AutoCrud\ColumnTypes;

class StringType extends ColumnType
{
    public function validationRules(): array
    {
        return ['required', 'string', 'max:255'];
    }
}
