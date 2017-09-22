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
            $this->commands('command.migration-tools.check-status');
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            'command.migration-tools.check-status',
            function ($app) {
                return new CheckStatusCommand($app['migrator']);
            }
        );
    }
}
