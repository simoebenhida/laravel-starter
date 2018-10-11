<?php

namespace Mohamedbenhida\LaravelStart;

use Illuminate\Support\Arr;
use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Console\Presets\Preset as BasePreset;

class Preset extends BasePreset
{
    public static $removeBootstrap = false;

    public static function setUpTailwind()
    {
        static::ensureComponentDirectoryExists();
        static::updatePackages();
        static::updateStyles();
        static::updateWebpackConfiguration();
        static::updateTemplates();
        static::removeNodeModules();
    }


    public static function setUpBootstrap()
    {
        static::$removeBootstrap = true;
        static::ensureComponentDirectoryExists();
        static::updatePackages();
        static::updateJavaScript();
        static::removeNodeModules();
    }

    protected static function updatePackageArray(array $packages)
    {
        return array_merge([
            'laravel-mix-purgecss' => '^2.2.0',
            'postcss-nesting' => '^5.0.0',
            'postcss-import' => '^11.1.0',
            'tailwindcss' => '>=0.6.1',
        ], static::$removeBootstrap ? Arr::except($packages, [
            'bootstrap',
            'bootstrap-sass',
            'jquery'
        ]) : $packages);
    }

    protected static function updateWebpackConfiguration()
    {
        copy(__DIR__.'/stubs/webpack.mix.js', base_path('webpack.mix.js'));
    }

    protected static function updateStyles()
    {
        tap(new Filesystem, function ($files) {
            $files->deleteDirectory(resource_path('assets/sass'));
            $files->delete(public_path('css/app.css'));

            if (! $files->isDirectory($directory = resource_path('assets/css'))) {
                $files->makeDirectory($directory, 0755, true);
            }
        });

        copy(__DIR__.'/stubs/resources/assets/css/app.css', resource_path('css/app.css'));
    }

    protected static function updateJavaScript()
    {
        tap(new Filesystem, function ($files) {
            $files->delete(public_path('js/app.js'));
        });

        copy(__DIR__.'/stubs/app.js', resource_path('js/app.js'));
        copy(__DIR__.'/stubs/bootstrap.js', resource_path('js/bootstrap.js'));
    }

    protected static function updateTemplates()
    {
        tap(new Filesystem, function ($files) {
            $files->delete(resource_path('views/home.blade.php'));
            $files->delete(resource_path('views/welcome.blade.php'));
            $files->copyDirectory(__DIR__.'/stubs/views', resource_path('views'));
        });
    }
}
