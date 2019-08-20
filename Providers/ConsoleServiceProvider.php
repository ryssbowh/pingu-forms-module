<?php

namespace Pingu\Forms\Providers;

use Illuminate\Support\ServiceProvider;
use Pingu\Forms\Console\MakeFormCommand;
use Pingu\Forms\Console\ModuleMakeFormCommand;

class ConsoleServiceProvider extends ServiceProvider
{
    protected $defer = true;

    protected $commands = [
        MakeFormCommand::class,
        ModuleMakeFormCommand::class
    ];

    /**
     * Register the commands.
     */
    public function register()
    {
        $this->commands($this->commands);
    }

    /**
     * @return array
     */
    public function provides()
    {
        return $this->commands;
    }
}
