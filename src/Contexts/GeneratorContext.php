<?php

namespace ObadaAz\AutoCrud\Contexts;

class GeneratorContext
{
    public function __construct(
        public string $model,
        public array $columns = [],
        public bool $isApi = false
    ) {
        //
    }
}