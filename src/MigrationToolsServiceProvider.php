<?php

namespace NFarrington\LaravelMigrationTools;

use Illuminate\Support\ServiceProvider;
use NFarrington\LaravelMigrationTools\Console\CheckStatusCommand;

class MigrationToolsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                CheckStatusCommand::class,
            ]);
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
