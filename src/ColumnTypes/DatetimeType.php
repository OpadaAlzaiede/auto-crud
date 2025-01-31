<?php

namespace ObadaAz\AutoCrud\ColumnTypes;

class DatetimeType extends ColumnType
{
    /**
     * @return array
     */
    public function validationRules(): array
    {
        return ['required', 'datetime'];
    }
}
