<?php

namespace ObadaAz\AutoCrud\Contracts;

use ObadaAz\AutoCrud\Contexts\GeneratorContext;

interface GeneratorContract
{
    public function generate(GeneratorContext $generatorContext): void;
}