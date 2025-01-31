<?php

namespace ObadaAz\AutoCrud\Generators;

use ObadaAz\AutoCrud\Contexts\GeneratorContext;
use ObadaAz\AutoCrud\Contracts\GeneratorContract;
use ObadaAz\AutoCrud\Services\FileHandler;

class ModelGenerator implements GeneratorContract
{
    public function __construct(protected FileHandler $fileHandler)
    {
        //
    }


    /**
     * @param GeneratorContext $generatorContext
     *
     */
    public function generate(GeneratorContext $generatorContext): void
    {
        $model = $generatorContext->model;
        $columns = $generatorContext->columns;

        $stub = 'model.stub';
        $columnNames = array_column($columns, 'name');
        $columnsString = "'" . implode("', '", $columnNames) . "'";

        $content = $this->fileHandler->getStubContent($stub, [
            '{{model}}' => $model,
            '{{columns}}' => $columnsString
        ]);

        $path = app_path("Models/{$model}.php");
        $this->fileHandler->createFile($path, $content);
    }
}
