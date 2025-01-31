<?php

namespace ObadaAz\AutoCrud;


use ObadaAz\AutoCrud\Contexts\GeneratorContext;
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
        $generatorContext = new GeneratorContext($this->model, $this->columns, $isApi);

        $this->migrationGenerator->generate($generatorContext);
        $this->modelGenerator->generate($generatorContext);
        $this->controllerGenerator->generate($generatorContext);
        $this->formRequestGenerator->generate($generatorContext);
        $this->routeGenerator->generate($generatorContext);

        if (!$isApi) {
            $this->viewGenerator->generate($generatorContext);
        }

        if ($isApi) {
            $this->resourceGenerator->generate($generatorContext);
        }
    }
}
