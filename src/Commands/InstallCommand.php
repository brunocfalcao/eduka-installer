<?php

namespace Eduka\Installer\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eduka:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installs Eduka. Go make courses!';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("
             _   _
            | \ | |
            |  \| | ___ _ __ ___ _   _ ___
            | . ` |/ _ \ '__/ _ \ | | / __|
            | |\  |  __/ | |  __/ |_| \__ \
            \_| \_/\___|_|  \___|\__,_|___/

            ");

        ray()->ban();
        ray(glob(database_path('migrations/*.php')));

        $this->info('Starting installation...');
        $this->info('');

        $this->info('Please apply the following changes in your .env:');
        $this->info('');
        $this->info('CACHE_DRIVER=redis');
        $this->info('QUEUE_CONNECTION=redis');

        $name = $this->ask('Continue?');
        $this->info('');

        /**
         * Register here all the packages that will generate migration
         * files that will needed to be copied into the tenants folder.
         **/
        $this->info('Installing spatie/laravel-medialibrary...');
        $this->executeCommand('composer require spatie/laravel-medialibrary');

        $this->info('Publishing media library migrations...');
        $this->call('vendor:publish', [
            '--provider' => 'Spatie\MediaLibrary\MediaLibraryServiceProvider',
            '--tag' => 'migrations',
            '--force' => 1,
        ]);

        $this->info('Publishing media library configuration...');
        $this->call('vendor:publish', [
            '--provider' => 'Spatie\MediaLibrary\MediaLibraryServiceProvider',
            '--tag' => 'config',
            '--force' => 1,
        ]);

        $this->info('Creating folder database/migrations/tenant...');
        Storage::makeDirectory(database_path('migrations/tenant'));
        $this->info('');

        $this->info('Copying default migrations into tenant folder...');
        $files = glob(database_path('migrations/*.php'));
        foreach ($files as $file) {
            $parts = collect(explode('/', str_replace("\\", "/", $file)));

            $filename = $parts->pop();
            $path = $parts->join('/');

            copy(
                $path.'/'.$filename,
                $path.'/tenant/'.$filename
            );
        }

        $this->info('Installing predis/predis...');
        $this->executeCommand('composer require predis/predis');

        $this->info('Refreshing database...');
        $this->call('migrate:fresh', [
            '--force' => 1
        ]);

        $this->info('Installing stancl/tenancy...');
        $this->executeCommand('composer require stancl/tenancy');
        $this->call('tenancy:install');
        $this->call('migrate', [
            '--force' => 1
        ]);

        $this->info('Installing eduka cube...');
        $this->executeCommand('composer require brunocfalcao/eduka-cube');

        $this->info('Installing eduka nereus...');
        $this->executeCommand('composer require brunocfalcao/eduka-nereus');
        $this->call('vendor:publish', [
            '--provider' => 'Eduka\Nereus\EdukaNereusServiceProvider',
            '--force' => 1
        ]);

        $this->info('Copying laravel migration files into migrations/tenant folder...');

        if (app()->environment() != 'production') {
            $this->info('Installing ray...');
            $this->executeCommand('composer require spatie/laravel-ray --dev');
        }

        $this->info('');
        $this->info('All done! Now go and create some awesome courses!');

        return 0;
    }

    /**
     * Run the given command as a process.
     *
     * @param  string  $command
     * @param  string  $path
     * @return void
     */
    protected function executeCommand($command, $path = null)
    {
        if ($path == null) {
            $path = getcwd();
        }

        $process = (Process::fromShellCommandline($command, $path))
                   ->setTimeout(null);

        if ('\\' !== DIRECTORY_SEPARATOR &&
            file_exists('/dev/tty') &&
            is_readable('/dev/tty')) {
            $process->setTty(true);
        }

        $process->run(function ($type, $line) {
            $this->output->write($line);
        });
    }
}
