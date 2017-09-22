<?php

namespace NFarrington\LaravelMigrationTools;

use Illuminate\Support\ServiceProvider;
use NFarrington\LaravelMigrationTools\Console\CheckStatusCommand;

class MigrationToolsServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->commands('command.migration-tools.check-status');
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

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('command.migration-tools.check-status');
    }
}
