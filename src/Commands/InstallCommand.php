<?php

namespace Eduka\Installer\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\File;
//use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

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

        if (! $this->checkRequirements()) {
            return;
        }

        $this->importEdukaNereus();

        dd('ok');

        $this->publishLaravelMigrations();

        $this->publishLaravelResources();

        $this->publishEdukaResources();

        $this->runMigrateFresh();

        //$this->concatenateDotEnv();

        $this->organizeFileTree();

        return Command::SUCCESS;
    }

    protected function publishLaravelMigrations()
    {
        $this->info('Publishing specific laravel migration files...');
        run('php artisan queue:batches-table');
        run('php artisan queue:failed-table');
    }

    protected function concatenateDotEnv()
    {
        $this->info('Concatenating eduka dotDev into the Laravel dotDev...');
        $customEnvPath = __DIR__.'/../../resources/dotenv/dotenv';
        $laravelEnvPath = base_path('.env');
        $customEnvContent = File::get($customEnvPath);
        File::append($laravelEnvPath, $customEnvContent);
    }

    protected function importEdukaNereus()
    {
        $this->info('Importing Eduka Nereus from composer...');
        $result = Process::run('composer require brunocfalcao/eduka-nereus')->throw();
        //$process = new Process(['composer', 'require', 'brunocfalcao/eduka-nereus']);
        //$result = $process->run();

        /*
        try {
            if (! $process->isSuccessful()) {
                $this->error($result->output());
                //return $result->errorOutput();
            }
        } catch (ProcessFailedException $e) {
            return $result->errorOutput();
        }
        */
    }

    protected function runMigrateFresh()
    {
        $migrateFreshProcess = new Process(['php', 'artisan', 'migrate:fresh', '--force']);
        $output = $migrateFreshProcess->run();

        $this->info($output);
    }

    protected function organizeFileTree()
    {
        $this->info('Organizing project files...');

        /**
         * We don't need the app/Models since we will use the Eduka models
         * directly.
         */
        File::deleteDirectory(base_path('app/Models'));

        File::delete(base_path('app/Nova/User.php'));

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
        // Verify if Nova is installed. If it's not, then exit.
        $this->info('Checking if Nova is installed...');
        if (! Application::getInstance()->getProvider(\App\Providers\NovaServiceProvider::class)) {
            $this->error('Nova is not installed or not booted. Please verify your Nova installation.');

            return false;
        }

        return true;
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
