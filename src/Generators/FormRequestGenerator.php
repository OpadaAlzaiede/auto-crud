<?php

namespace ObadaAz\AutoCrud\Generators;

use ObadaAz\AutoCrud\Services\FileHandler;
use ObadaAz\AutoCrud\ColumnTypes\ColumnTypeFactory;
use ObadaAz\AutoCrud\Contexts\GeneratorContext;
use ObadaAz\AutoCrud\Contracts\GeneratorContract;

class FormRequestGenerator implements GeneratorContract
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

        $requests = ['Store', 'Update'];

        foreach ($requests as $request) {
            $rules = [];
            foreach ($columns as $column) {
                $columnType = ColumnTypeFactory::make($column['type']);
                $rules[$column['name']] = $columnType->getValidationRule();
            }
            $rulesString = $this->formatRules($rules);

            $content = $this->fileHandler->getStubContent("form-request.stub", [
                '{{model}}' => $model,
                '{{request}}' => $request,
                '{{rules}}' => $rulesString
            ]);
            $path = app_path("Http/Requests/{$model}/{$request}{$model}Request.php");
            $this->fileHandler->createFile($path, $content);
        }
    }

    /**
     * @param array $rules
     *
     * @return string
     */
    protected function formatRules(array $rules): string
    {
        $formattedRules = [];
        foreach ($rules as $column => $rule) {
            $formattedRules[] = "'$column' => '$rule'";
        }
        return implode(",\n            ", $formattedRules);
    }
}
