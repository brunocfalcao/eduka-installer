<?php

namespace Eduka\Installer\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    protected $signature = 'eduka:install';

    protected $description = 'Installs eduka for the first time.';

    public function handle()
    {
        $this->alert('Welcome to Eduka - Best LMS framework for Laravel');

        $this->checkRequirements();

        $this->organizeFileTree();

        $this->publishLaravelResources();

        $this->publishEdukaResources();

        return Command::SUCCESS;
    }

    protected function organizeFileTree()
    {
        File::deleteDirectory(base_path('app/Models'));
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
        $this->info('-=   Eduka resources publish start   =-');

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
