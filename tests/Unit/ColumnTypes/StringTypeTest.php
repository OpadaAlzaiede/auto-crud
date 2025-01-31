<?php

use ObadaAz\AutoCrud\ColumnTypes\StringType;

it('returns correct validation rules for string type', function () {
    $stringType = new StringType();

    expect($stringType->validationRules())->toBe(['required', 'string', 'max:255']);

    expect($stringType->getValidationRule())->toBe('required|string|max:255');
});