<?php

namespace Eduka\Installer;

use Eduka\Installer\Commands\InstallCommand;
use Illuminate\Support\ServiceProvider;

class InstallerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerCommands();
        $this->overrideResources();
    }

    public function register()
    {
        //
    }

    protected function overrideResources()
    {
        $this->publishes([
            __DIR__.'/../resources/overrides/' => base_path('/'),
        ]);
    }

    protected function registerCommands()
    {
        $this->commands([
            InstallCommand::class,
        ]);
    }
}
