<?php

use ObadaAz\AutoCrud\ColumnTypes\DatetimeType;

it('returns correct validation rules for datetime type', function () {
    $datetimeType = new DatetimeType();

    expect($datetimeType->validationRules())->toBe(['required', 'datetime']);

    expect($datetimeType->getValidationRule())->toBe('required|datetime');
});