<?php

namespace ObadaAz\AutoCrud\Commands;

use Illuminate\Console\Command;
use ObadaAz\AutoCrud\CrudGenerator;

class MakeCrudCommand extends Command
{
    protected $signature = 'make:crud {model : The name of the model}
                            {--api : Generate API-only CRUD}';

    protected $description = 'Generate CRUD operations for a model.';

    public function handle(CrudGenerator $generator): void
    {
        $model = ucfirst($this->argument('model'));
        $isApi = $this->option('api');

        $columns = [];
        $this->info("Let's define the columns for the $model table.");
        while ($this->confirm('Do you want to add a column?')) {
            $name = $this->ask('Enter the column name');
            $type = $this->choice('Select the column type', [
                'string', 'integer', 'date', 'datetime'
            ]);
            $columns[] = compact('name', 'type');
        }

        $generator->setModel($model)
                ->setColumns($columns)
                ->generate($isApi);

        $this->info("CRUD for $model generated successfully!");
    }
}
