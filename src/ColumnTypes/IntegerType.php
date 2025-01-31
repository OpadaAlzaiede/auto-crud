<?php

namespace ObadaAz\AutoCrud\ColumnTypes;

class IntegerType extends ColumnType
{
    /**
     * @return array
     */
    public function validationRules(): array
    {
        return ['required', 'integer'];
    }
}
