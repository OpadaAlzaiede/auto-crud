<?php

namespace ObadaAz\AutoCrud\ColumnTypes;

class IntegerType extends ColumnType
{
    public function validationRules(): array
    {
        return ['required', 'integer'];
    }
}
