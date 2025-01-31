<?php

namespace ObadaAz\AutoCrud\ColumnTypes;

class ColumnTypeFactory
{
    /**
     * @param string $type
     *
     * @return ColumnType
     */
    public static function make(string $type): ColumnType
    {
        return match ($type) {
            'string' => new StringType(),
            'integer' => new IntegerType(),
            'date' => new DateType(),
            'datetime' => new DatetimeType(),
            default => throw new \InvalidArgumentException("Unsupported column type: $type"),
        };
    }
}
