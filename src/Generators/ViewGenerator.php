<?php

namespace ObadaAz\AutoCrud\Generators;

use Illuminate\Support\Str;
use ObadaAz\AutoCrud\Contexts\GeneratorContext;
use ObadaAz\AutoCrud\Contracts\GeneratorContract;
use ObadaAz\AutoCrud\Services\FileHandler;

class ViewGenerator implements GeneratorContract
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

        $views = ['index', 'create', 'edit'];
        $variable = Str::lower($model);
        $resource = Str::plural($variable);

        foreach ($views as $view) {
            $replacements = [
                '{{model}}' => $model,
                '{{resource}}' => $resource,
                '{{variable}}' => $variable,
            ];

            $content = $this->fileHandler->getStubContent("$view.stub", $replacements);
            $content = $this->buildViewTableContent($content, $columns, $variable);
            $content = $this->buildViewFormContent($content, $columns);

            $path = resource_path("views/{$resource}/$view.blade.php");
            $this->fileHandler->createFile($path, $content);
        }
    }

    /**
     * @param string $stub
     * @param array $columns
     * @param string $variable
     *
     * @return string
     */
    protected function buildViewTableContent(string $stub, array $columns, string $variable): string
    {
        $headers = "";
        $rows = "";

        foreach ($columns as $column) {
            $headers .= "<th>{$column['name']}</th>\n            ";
        }

        foreach ($columns as $column) {
            $rows .= "<td>\${$variable}->{$column['name']}</td>\n            ";
        }

        return str_replace(
            ['{{headers}}', '{{rows}}'],
            [$headers, $rows],
            $stub
        );
    }

    /**
     * @param string $stub
     * @param array $columns
     *
     * @return string
     */
    protected function buildViewFormContent(string $stub, array $columns): string
    {
        $inputs = "";

        foreach ($columns as $column) {
            $name = $column['name'];
            $type = $column['type'];

            $inputs .= "<label for='{$name}'>{$name}</label>\n            ";
            $inputs .= "<input name='{$name}' id='{$name}' type='{$type}'/>\n            ";
        }

        return str_replace(
            '{{inputs}}',
            $inputs,
            $stub
        );
    }
}
