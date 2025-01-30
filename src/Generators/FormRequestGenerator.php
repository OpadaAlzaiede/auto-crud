<?php

namespace ObadaAz\AutoCrud\Generators;

use ObadaAz\AutoCrud\Services\FileHandler;
use ObadaAz\AutoCrud\ColumnTypes\ColumnTypeFactory;

class FormRequestGenerator
{
    public function __construct(protected FileHandler $fileHandler)
    {
        //
    }

    public function generate(string $model, array $columns): void
    {
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

    protected function formatRules(array $rules): string
    {
        $formattedRules = [];
        foreach ($rules as $column => $rule) {
            $formattedRules[] = "'$column' => '$rule'";
        }
        return implode(",\n            ", $formattedRules);
    }
}