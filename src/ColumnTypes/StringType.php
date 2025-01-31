<?php

namespace ObadaAz\AutoCrud\ColumnTypes;

class StringType extends ColumnType
{
    /**
     * @return array
     */
    public function validationRules(): array
    {
        return ['required', 'string', 'max:255'];
    }
}
