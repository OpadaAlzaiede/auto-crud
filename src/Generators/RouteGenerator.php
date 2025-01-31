<?php

namespace ObadaAz\AutoCrud\Generators;

use Illuminate\Support\Str;
use ObadaAz\AutoCrud\Services\FileHandler;

class RouteGenerator
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
