<?php

namespace Eduka\Installer\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class InstallCommand extends Command
{
    protected $signature = 'eduka:install';

    protected $description = 'Installs eduka for the first time.';

    /**
     * Installation logic:
     *
     * We should run the installation before running any other command after
     * installing a new laravel project, and requiring nova. We should not
     * run any nova installer command, that will run here via the eduka
     * installer.
     */
    public function handle()
    {
        $this->alert('Welcome to Eduka - Best LMS framework for Laravel');

        $this->confirm('Welcome to the Eduka installation. This installation will run also the Nova commands, so no need to run them before.');

        $this->checkRequirements();

        $this->organizeFileTree();

        $this->publishLaravelResources();

        $this->publishEdukaResources();

        $this->runMigrateFresh();

        return Command::SUCCESS;
    }

    protected function runMigrateFresh()
    {
        $migrateFreshProcess = new Process(['php', 'artisan', 'migrate:fresh', '--force']);
        $migrateFreshProcess->run();

        try {
            if (! $migrateFreshProcess->isSuccessful()) {
                throw new ProcessFailedException($migrateFreshProcess);
            }
        } catch (ProcessFailedException $e) {
            return $this->error($e->getMessage());
        }
    }

    protected function organizeFileTree()
    {
        /**
         * We don't need the app/Models since we will use the Eduka models
         * directly.
         */
        File::deleteDirectory(base_path('app/Models'));

        /**
         * We also delete files from the migrations default folder that
         * are no longer neededd. */
        $toDelete = glob(database_path('/*/*create_personal_access_tokens*.*'));

        foreach ($toDelete as $file) {
            File::delete($file);
        }

        // Additional config files to delete.
        File::delete(base_path('config/sanctum.php'));
    }

    protected function checkRequirements()
    {
        $this->info('');
        $this->info('-=   Requirements check start   =-');
        $this->info('-= Requirements check completed =-');
        $this->info('');
    }

    protected function publishLaravelResources()
    {
        $this->info('');
        $this->info('-=   Laravel resources publish start   =-');

        $this->call('vendor:publish', [
            '--force' => 'true',
            '--tag' => 'laravel-mail',
        ]);

        $this->info('-= Laravel resources publish completed =-');
        $this->info('');
    }

    protected function publishEdukaResources()
    {
        $this->info('');
        $this->info('-=   Eduka resources publish start / overrides   =-');

        $this->call('vendor:publish', [
            '--force' => 'true',
            '--provider' => 'Eduka\\Installer\\InstallerServiceProvider',
        ]);

        $this->call('vendor:publish', [
            '--force' => 'true',
            '--provider' => 'Eduka\\Nereus\\NereusServiceProvider',
        ]);

        $this->call('vendor:publish', [
            '--force' => 'true',
            '--provider' => 'Eduka\\Services\\ServicesServiceProvider',
        ]);

        $this->info('-= Eduka resources publish completed =-');
        $this->info('');
    }
}
