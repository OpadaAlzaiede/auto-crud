<?php

use ObadaAz\AutoCrud\ColumnTypes\ColumnType;

it('formats validation rules correctly', function () {

    $mock = new class extends ColumnType {
        public function validationRules(): array
        {
            return ['required', 'string', 'max:255'];
        }
    };

    expect($mock->getValidationRule())->toBe('required|string|max:255');
});