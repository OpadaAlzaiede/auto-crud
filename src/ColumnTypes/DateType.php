<?php

namespace ObadaAz\AutoCrud\ColumnTypes;

class DateType extends ColumnType
{
    /**
     * @return array
     */
    public function validationRules(): array
    {
        return ['required', 'date'];
    }
}
