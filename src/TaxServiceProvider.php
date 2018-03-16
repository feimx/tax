<?php

namespace FeiMx\Tax;

use Illuminate\Support\ServiceProvider;

class TaxServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if (! class_exists('CreateTaxesTables')) {
            $timestamp = date('Y_m_d_His', time());
            $this->publishes([
                __DIR__.'/../database/migrations/create_taxes_tables.php.stub' => $this->app->databasePath()."/migrations/{$timestamp}_create_taxes_tables.php",
            ], 'migrations');
        }

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
