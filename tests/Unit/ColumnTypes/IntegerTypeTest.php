<?php

use ObadaAz\AutoCrud\ColumnTypes\IntegerType;

it('returns correct validation rules for integer type', function () {
    $integerType = new IntegerType();

    expect($integerType->validationRules())->toBe(['required', 'integer']);

    expect($integerType->getValidationRule())->toBe('required|integer');
});