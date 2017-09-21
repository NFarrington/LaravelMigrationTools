<?php

namespace NFarrington\LaravelMigrationTools\Console;

use Illuminate\Database\Console\Migrations\BaseCommand as MigrationsBaseCommand;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Support\Collection;

class CheckStatusCommand extends MigrationsBaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:check-status
                            {--database= : The database connection to use.}
                            {--path= : The path of migrations files to use.}
                            {--status-pending=0 : Exit code if pending migrations.}
                            {--status-complete=1 : Exit code if all migrations have run.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if there are migrations ready to migrate.';

    /**
     * The migrator instance.
     *
     * @var \Illuminate\Database\Migrations\Migrator
     */
    protected $migrator;

    /**
     * Create a new command instance.
     *
     * @param  \Illuminate\Database\Migrations\Migrator $migrator
     */
    public function __construct(Migrator $migrator)
    {
        parent::__construct();

        $this->migrator = $migrator;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $pendingExitCode = (int) $this->option('status-pending');
        $completedExitCode = (int) $this->option('status-complete');

        $this->migrator->setConnection($this->option('database'));

        if (! $this->migrator->repositoryExists()) {
            $this->error('No migrations found.');

            return $completedExitCode;
        }

        $ran = $this->migrator->getRepository()->getRan();

        if (count($migrations = $this->getAllMigrationFiles()) === 0) {
            $this->error('No migrations found');

            return $completedExitCode;
        } elseif ($pending = count($this->getPendingMigrations($ran))) {
            $this->info("Pending migrations: <fg=red>$pending</fg=red>");

            return $pendingExitCode;
        } else {
            $this->info('No pending migrations.');

            return $completedExitCode;
        }
    }

    /**
     * Get the migrations that have not yet been run.
     *
     * @param  array  $ran
     * @return array
     */
    protected function getPendingMigrations(array $ran)
    {
        return Collection::make($this->getAllMigrationFiles())
            ->reject(function ($file) use ($ran) {
                return in_array($this->migrator->getMigrationName($file), $ran);
            })->values()->all();
    }

    /**
     * Get an array of all of the migration files.
     *
     * @return array
     */
    protected function getAllMigrationFiles()
    {
        return $this->migrator->getMigrationFiles($this->getMigrationPaths());
    }
}
