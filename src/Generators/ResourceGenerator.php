<?php

namespace ObadaAz\AutoCrud\Generators;

use ObadaAz\AutoCrud\Services\FileHandler;

class ResourceGenerator
{
    public function __construct(protected FileHandler $fileHandler)
    {
        //
    }

    public function generate(string $model, array $columns): void
    {
        $content = $this->fileHandler->getStubContent("resource.stub", [
            '{{model}}' => $model
        ]);
        $content = $this->buildResourceContent($content, $columns);

        $path = app_path("Http/Resources/{$model}Resource.php");
        $this->fileHandler->createFile($path, $content);
    }

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