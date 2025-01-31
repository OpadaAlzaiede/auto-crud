<?php

namespace ObadaAz\AutoCrud\Generators;

use Illuminate\Support\Str;
use ObadaAz\AutoCrud\Services\FileHandler;

class ControllerGenerator
{
    public function __construct(protected FileHandler $fileHandler)
    {
        //
    }

    /**
     * @param string $model
     * @param bool $isApi
     *
     */
    public function generate(string $model, bool $isApi): void
    {
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
