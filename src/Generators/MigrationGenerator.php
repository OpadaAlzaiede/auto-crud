<?php

namespace ObadaAz\AutoCrud\Generators;

use Illuminate\Support\Str;
use ObadaAz\AutoCrud\Contexts\GeneratorContext;
use ObadaAz\AutoCrud\Contracts\GeneratorContract;
use ObadaAz\AutoCrud\Services\FileHandler;

class MigrationGenerator implements GeneratorContract
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

        $stub = $this->fileHandler->getStubContent('migration.stub', [
            '{{model}}' => $model
        ]);

        $content = $this->buildMigrationContent($stub, $model, $columns);

        $fileName = date('Y_m_d_His') . '_create_' . Str::plural(Str::lower($model)) . '_table.php';
        $path = database_path('migrations/' . $fileName);

        $this->fileHandler->createFile($path, $content);
    }

    /**
     * @param string $stub
     * @param string $model
     * @param array $columns
     *
     * @return string
     */
    protected function buildMigrationContent(string $stub, string $model, array $columns): string
    {
        $table = Str::plural(strtolower($model));
        $columnsContent = '';

        foreach ($columns as $column) {
            $columnsContent .= "\$table->{$column['type']}('{$column['name']}');\n            ";
        }

        return str_replace(
            ['{{table}}', '{{columns}}'],
            [$table, $columnsContent],
            $stub
        );
    }
}
