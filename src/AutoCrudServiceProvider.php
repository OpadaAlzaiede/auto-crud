<?php

namespace ObadaAz\AutoCrud;

use Illuminate\Support\ServiceProvider;

class AutoCrudServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Commands\MakeCrudCommand::class,
            ]);
        }
    }

    public function register(): void
    {
        // Register any bindings or services here
    }
}
