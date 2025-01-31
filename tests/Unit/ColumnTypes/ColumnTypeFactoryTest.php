<?php

use ObadaAz\AutoCrud\ColumnTypes\ColumnTypeFactory;
use ObadaAz\AutoCrud\ColumnTypes\StringType;
use ObadaAz\AutoCrud\ColumnTypes\IntegerType;
use ObadaAz\AutoCrud\ColumnTypes\DateType;
use ObadaAz\AutoCrud\ColumnTypes\DatetimeType;

it('creates a StringType for "string"', function () {
    $type = ColumnTypeFactory::make('string');
    expect($type)->toBeInstanceOf(StringType::class);
});

it('creates an IntegerType for "integer"', function () {
    $type = ColumnTypeFactory::make('integer');
    expect($type)->toBeInstanceOf(IntegerType::class);
});

it('creates a DateType for "date"', function () {
    $type = ColumnTypeFactory::make('date');
    expect($type)->toBeInstanceOf(DateType::class);
});

it('creates a DatetimeType for "datetime"', function () {
    $type = ColumnTypeFactory::make('datetime');
    expect($type)->toBeInstanceOf(DatetimeType::class);
});

it('throws an exception for unsupported types', function () {
    expect(fn() => ColumnTypeFactory::make('unsupported'))
        ->toThrow(\InvalidArgumentException::class, 'Unsupported column type: unsupported');
});