<?php

namespace Eduka\Installer\Commands;

use Illuminate\Support\Str;
use Eduka\Cube\Models\Tenant;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class CreateCourseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eduka:create-course';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new course (tenant and configuration).';

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

        $this->info('Creating new course...');
        $this->info('');

        $name = $this->ask('What is the course name (E.g.: Mastering Nova)?');
        $domain = $this->ask('What is the domain name (E.g.: masteringnova.com)?');

        $this->info('');
        $this->info('Creating tenant...');

        $tenant = Tenant::create([
            'name' => $name,
            'tenancy_db_name' => str_replace(' ', '-', strtolower($name))
        ]);

        $this->info('Creating domain...');
        $tenant->domains()->create([
            'domain' => $domain,
        ]);

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
