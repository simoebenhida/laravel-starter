<?php

namespace Mohamedbenhida\LaravelStart;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Console\PresetCommand;
use Mohamedbenhida\LaravelStart\Commands\StartCommand;

class StartServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                StartCommand::class,
            ]);
        }
    }
}
