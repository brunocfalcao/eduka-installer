<?php

namespace Eduka\Installer;

use Illuminate\Support\ServiceProvider;
use Eduka\Installer\Commands\InstallCommand;
use Eduka\Installer\Commands\CreateCourseCommand;

class EdukaInstallerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        /*
         * On a command line scope Nereus cannot be instanciated. Only
         * the commands and specific assets publish. This is due to avoid
         * a domain scope context that may not exist.
         */
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                CreateCourseCommand::class
            ]);
        }
    }

    public function register()
    {
    }
}
