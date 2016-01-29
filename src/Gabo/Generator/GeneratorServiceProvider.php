<?php

namespace Gabo\Generator;

use Illuminate\Support\ServiceProvider;
use Gabo\Generator\Commands\APIGeneratorCommand;
use Gabo\Generator\Commands\PublisherCommand;
use Gabo\Generator\Commands\ScaffoldAPIGeneratorCommand;
use Gabo\Generator\Commands\ScaffoldGeneratorCommand;

class GeneratorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $configPath = __DIR__.'/../../../config/generator.php';

        $this->publishes([
            $configPath => config_path('generator.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('gabo.generator.publish', function ($app) {
            return new PublisherCommand();
        });

        $this->app->singleton('gabo.generator.api', function ($app) {
            return new APIGeneratorCommand();
        });

        $this->app->singleton('gabo.generator.scaffold', function ($app) {
            return new ScaffoldGeneratorCommand();
        });

        $this->app->singleton('gabo.generator.scaffold_api', function ($app) {
            return new ScaffoldAPIGeneratorCommand();
        });

        $this->commands([
            'gabo.generator.publish',
            'gabo.generator.api',
            'gabo.generator.scaffold',
            'gabo.generator.scaffold_api',
        ]);
    }
}
