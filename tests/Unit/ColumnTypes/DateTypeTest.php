<?php

use ObadaAz\AutoCrud\ColumnTypes\DateType;

it('returns correct validation rules for date type', function () {
    $dateType = new DateType();

    expect($dateType->validationRules())->toBe(['required', 'date']);

    expect($dateType->getValidationRule())->toBe('required|date');
});