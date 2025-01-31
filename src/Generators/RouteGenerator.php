<?php

namespace ObadaAz\AutoCrud\Generators;

use Illuminate\Support\Str;
use ObadaAz\AutoCrud\Contexts\GeneratorContext;
use ObadaAz\AutoCrud\Contracts\GeneratorContract;
use ObadaAz\AutoCrud\Services\FileHandler;

class RouteGenerator implements GeneratorContract
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
        $isApi = $generatorContext->isApi;

        $stub = $isApi ? 'api-routes.stub' : 'web-routes.stub';
        $routesFile = $isApi ? 'api.php' : 'web.php';

        $content = $this->fileHandler->getStubContent($stub, [
            '{{model}}' => $model,
            '{{resource}}' => Str::lower(Str::plural($model))
        ]);

        $path = base_path("routes/{$routesFile}");
        $this->fileHandler->appendToFile($path, $content);
    }
}
