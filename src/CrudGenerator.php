<?php

namespace ObadaAz\AutoCrud;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use ObadaAz\AutoCrud\ColumnTypes\ColumnTypeFactory;

class CrudGenerator
{
    protected string $model;
    protected array $columns;

    public function setModel(string $model): self {

        $this->model = $model;

        return $this;
    }

    public function setColumns(array $columns): self {

        $this->columns = $columns;

        return $this;
    }

    public function generate(bool $isApi = false): void {

        $this->createMigration();
        $this->createModel();
        $this->createController($isApi);
        $this->createFormRequests();
        $this->createRoutes($isApi);

        if(!$isApi) {
            $this->createViews();
            exit;

        }

        if ($isApi) {
            $this->createResources();
        }
    }

    protected function createModel(): void {

        $stub = 'model.stub';

        $columnNames = array_column($this->columns, 'name');
        $columnsString = "'" . implode("', '", $columnNames) . "'";

        $content = $this->getStubContent($stub, [
            '{{model}}' => $this->model,
            '{{columns}}' => $columnsString
        ]);

        $path = app_path("Models/{$this->model}.php");
        File::ensureDirectoryExists(dirname($path));
        File::put($path, $content);
    }

    protected function createController(bool $isApi): void {

        $stub = $isApi ? 'api-controller.stub' : 'controller.stub';
        $variable = Str::lower($this->model);

        $content = $this->getStubContent($stub, [
            '{{model}}' => $this->model,
            '{{variable}}' => $variable,
            '{{resource}}' => Str::plural($variable) 
        ]);

        $path = $isApi ? app_path("Http/Controllers/Api/{$this->model}Controller.php") : app_path("Http/Controllers/{$this->model}Controller.php") ;
        File::ensureDirectoryExists(dirname($path));
        File::put($path, $content);
    }

    protected function createViews(): void {

        $views = ['index', 'create', 'edit'];
        $variable = Str::lower($this->model);
        $resource = Str::plural($variable);

        foreach ($views as $view) {

            $replacements = [
                '{{model}}' => $this->model,
                '{{resource}}' => $resource,
                '{{variable}}' => $variable,
            ];

            $content = $this->getStubContent("$view.stub", $replacements);
            $content = $this->buildViewTableContent($content);
            
            $content = $this->buildViewFormContent($content);

            $path = resource_path("views/{$resource}/$view.blade.php");
            File::ensureDirectoryExists(dirname($path));
            File::put($path, $content);
        }
    }

    protected function createRoutes(bool $isApi): void {

        $stub = $isApi ? 'api-routes.stub' : 'web-routes.stub';
        $routesFile = $isApi ? 'api.php' : 'web.php';

        $content = $this->getStubContent($stub, [
            '{{model}}' => $this->model,
            '{{resource}}' => Str::lower(Str::plural($this->model))
        ]);

        $path = base_path("routes/{$routesFile}");
        File::append($path, $content);
    }

    protected function createFormRequests(): void {

        $requests = ['Store', 'Update'];

        foreach ($requests as $request) {
            
            $rules = [];
            foreach($this->columns as $column) {
                $columnType = ColumnTypeFactory::make($column['type']);
                $rules[$column['name']] = $columnType->getValidationRule();
            }
            $rulesString = $this->formatRules($rules);

            $content = $this->getStubContent("form-request.stub", [
                '{{model}}' => $this->model,
                '{{request}}' => $request,
                '{{rules}}' => $rulesString
            ]);
            $path = app_path("Http/Requests/{$this->model}/{$request}{$this->model}Request.php");
            File::ensureDirectoryExists(dirname($path));
            File::put($path, $content);
        }
    }

    protected function createResources(): void {

        $resources = ['Resource', 'Collection'];

        foreach ($resources as $resource) {
            $content = $this->getStubContent("resource.stub", [
                '{{model}}' => $this->model
            ]);
            $path = app_path("Http/Resources/{$this->model}{$resource}.php");
            File::ensureDirectoryExists(dirname($path));
            File::put($path, $content);
        }
    }

    protected function createMigration(): void {

        $stub = $this->getStubContent('migration.stub', [
            '{{model}}' => $this->model
        ]);
        $content = $this->buildMigrationContent($stub);

        $fileName = date('Y_m_d_His') . '_create_' . Str::plural(Str::lower($this->model)) . '_table.php';
        $path = database_path('migrations/' . $fileName);

        File::put($path, $content);
    }

    protected function buildMigrationContent(string $stub): string {

        $table = Str::plural(strtolower($this->model));
        $columnsContent = '';

        foreach ($this->columns as $column) {
            $columnsContent .= "\$table->{$column['type']}('{$column['name']}');\n            ";
        }

        return str_replace(
            ['{{table}}', '{{columns}}'],
            [$table, $columnsContent],
            $stub
        );
    }

    protected function buildViewTableContent(string $stub): string {

        $headers = "";
        $rows = "";

        $variable = Str::lower($this->model);

        foreach ($this->columns as $column) {
            $headers .= "<th>{$column['name']}</th>\n            ";
        }

        foreach ($this->columns as $column) {
            $rows .= "<td>\${$variable}->{$column['name']}</td>\n            ";
        }

        return str_replace(
            ['{{headers}}', '{{rows}}'],
            [$headers, $rows],
            $stub
        );
    }

    protected function buildViewFormContent(string $stub): string {

        $inputs = "";

        foreach ($this->columns as $column) {
            $inputs .= "<label for={$column}>{$column}</label>";
            $inputs .= "<input name={$column} id={$column} type={$column['type']}/>\n            ";
        }

        return str_replace(
            '{{inputs}}',
            $inputs,
            $stub
        );
    }

    protected function getStubContent(string $stub, array $replacements): string
    {
        $content = File::get(__DIR__ . "/stubs/$stub");

        foreach($replacements as $key => $value) {

            $content = str_replace($key, $value, $content);
        }

        return $content;
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