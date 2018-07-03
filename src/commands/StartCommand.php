<?php

namespace Mohamedbenhida\LaravelStart\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Mohamedbenhida\LaravelStart\Preset;

class StartCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'start';
    protected $file;
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $file)
    {
        parent::__construct();
        $this->file = $file;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->confirm("Configure Database", true)) {
            $this->updateEnvFile('DB_HOST', $this->ask('Database Host (Press Enter if it is localhost)', '127.0.0.1'));
            $this->updateEnvFile('DB_DATABASE', $this->ask('Database Name', 'laravel'));
            $this->updateEnvFile('DB_USERNAME', $this->ask('Database Username', 'root'));
            $this->updateEnvFile('DB_PASSWORD', $this->secret('Database Password (Press Enter if there is no password)'));
        }

        //Add Tailwindcss
        if ($this->confirm("Install Tailwindcss", true)) {
            Preset::setUpTailwind();
            $this->info('Tailwind has installed.');
            $this->info('Create Tailwind config file "./node_modules/.bin/tailwind init [filename]" to compile your tailwindcss installation.');
        }
        if ($this->confirm("Remove Bootstrap and Jquery", true)) {
            Preset::setUpBootstrap();
            $this->info('You just removed bootstrap and jquery.');
            $this->info('Please run "yarn install && yarn run dev" to compile your fresh installation.');
        }
    }

    public function updateEnvFile($key, $value)
    {
        $path = app()->environmentFilePath();
        $this->file->put($path, str_replace("{$key}=".env($key), "{$key}={$value}", $this->file->get($path)));
    }
}
