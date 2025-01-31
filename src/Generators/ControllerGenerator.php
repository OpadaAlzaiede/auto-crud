<?php

namespace ObadaAz\AutoCrud\Generators;

use Illuminate\Support\Str;
use ObadaAz\AutoCrud\Contexts\GeneratorContext;
use ObadaAz\AutoCrud\Contracts\GeneratorContract;
use ObadaAz\AutoCrud\Services\FileHandler;

class ControllerGenerator implements GeneratorContract
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

        $stub = $isApi ? 'api-controller.stub' : 'controller.stub';
        $variable = Str::lower($model);

        $content = $this->fileHandler->getStubContent($stub, [
            '{{model}}' => $model,
            '{{variable}}' => $variable,
            '{{resource}}' => Str::plural($variable)
        ]);

        $path = $isApi ? app_path("Http/Controllers/Api/{$model}Controller.php") : app_path("Http/Controllers/{$model}Controller.php");
        $this->fileHandler->createFile($path, $content);
    }
}
