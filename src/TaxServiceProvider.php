<?php

namespace FeiMx\Tax;

use FeiMx\Tax\Contracts\Factory;
use FeiMx\Tax\TaxManager;
use Illuminate\Support\ServiceProvider;

class TaxServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/tax.php' => config_path('tax.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/tax.php', 'tax');
    }
}
