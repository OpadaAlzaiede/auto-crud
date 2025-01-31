<?php

namespace ObadaAz\AutoCrud;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use ObadaAz\AutoCrud\ColumnTypes\ColumnTypeFactory;
use ObadaAz\AutoCrud\Generators\{
    ModelGenerator,
    ControllerGenerator,
    MigrationGenerator,
    FormRequestGenerator,
    RouteGenerator,
    ViewGenerator,
    ResourceGenerator
};

class CrudGenerator
{
    protected string $model;
    protected array $columns;

    public function __construct(
        protected ModelGenerator $modelGenerator,
        protected ControllerGenerator $controllerGenerator,
        protected MigrationGenerator $migrationGenerator,
        protected FormRequestGenerator $formRequestGenerator,
        protected RouteGenerator $routeGenerator,
        protected ViewGenerator $viewGenerator,
        protected ResourceGenerator $resourceGenerator
    ) {
        //
    }

    /**
     * @param string $model
     *
     * @return self
     */
    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @param array $columns
     *
     * @return self
     */
    public function setColumns(array $columns): self
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * @param bool $isApi
     *
     */
    public function generate(bool $isApi = false): void
    {
        $this->migrationGenerator->generate($this->model, $this->columns);
        $this->modelGenerator->generate($this->model, $this->columns);
        $this->controllerGenerator->generate($this->model, $isApi);
        $this->formRequestGenerator->generate($this->model, $this->columns);
        $this->routeGenerator->generate($this->model, $isApi);

        if (!$isApi) {
            $this->viewGenerator->generate($this->model, $this->columns);
        }

        if ($isApi) {
            $this->resourceGenerator->generate($this->model, $this->columns);
        }
    }
}
