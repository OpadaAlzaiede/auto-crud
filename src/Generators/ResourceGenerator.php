<?php

namespace ObadaAz\AutoCrud\Generators;

use ObadaAz\AutoCrud\Contexts\GeneratorContext;
use ObadaAz\AutoCrud\Contracts\GeneratorContract;
use ObadaAz\AutoCrud\Services\FileHandler;

class ResourceGenerator implements GeneratorContract
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

        $content = $this->fileHandler->getStubContent("resource.stub", [
            '{{model}}' => $model
        ]);
        $content = $this->buildResourceContent($content, $columns);

        $path = app_path("Http/Resources/{$model}Resource.php");
        $this->fileHandler->createFile($path, $content);
    }


    /**
     * @param string $stub
     * @param array $columns
     *
     * @return string
     */
    protected function buildResourceContent(string $stub, array $columns): string
    {
        $fields = [];

        foreach ($columns as $column) {
            $name = $column['name'];
            $fields[] = "'$name' => \$this->$name";
        }
        $fields = implode(",\n            ", $fields);

        return str_replace(
            '{{fields}}',
            $fields,
            $stub
        );
    }
}
