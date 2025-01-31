<?php

namespace ObadaAz\AutoCrud\Services;

use Illuminate\Support\Facades\File;

class FileHandler
{
    /**
     * @param string $stub
     * @param array $replacements
     *
     * @return string
     */
    public function getStubContent(string $stub, array $replacements): string
    {
        $content = File::get(__DIR__ . "/../stubs/$stub");

        foreach ($replacements as $key => $value) {
            $content = str_replace($key, $value, $content);
        }

        return $content;
    }

    /**
     * @param string $path
     * @param string $content
     *
     */
    public function createFile(string $path, string $content): void
    {
        File::ensureDirectoryExists(dirname($path));
        File::put($path, $content);
    }

    /**
     * @param string $path
     * @param string $content
     *
     */
    public function appendToFile(string $path, string $content): void
    {
        File::append($path, $content . "\n");
    }
}
