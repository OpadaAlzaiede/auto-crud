<?php

namespace ObadaAz\AutoCrud\Generators;

use ObadaAz\AutoCrud\Services\FileHandler;

class ModelGenerator
{
    public function __construct(protected FileHandler $fileHandler)
    {
        //
    }


    /**
     * @param string $model
     * @param array $columns
     *
     */
    public function generate(string $model, array $columns): void
    {
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
