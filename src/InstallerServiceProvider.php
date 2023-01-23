<?php

namespace Eduka\Installer;

use Eduka\Installer\Commands\InstallCommand;
use Illuminate\Support\ServiceProvider;

class InstallerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerCommands();
    }

    public function register()
    {
        //
    }

    protected function registerCommands()
    {
        $this->commands([
            InstallCommand::class,
        ]);
    }
}
