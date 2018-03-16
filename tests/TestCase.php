<?php

namespace FeiMx\Tax\Tests;

use FeiMx\Tax\TaxServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    public function setup()
    {
        parent::setUp();
        $this->setUpDatabase($this->app);
    }

    protected function getPackageProviders($app)
    {
        return [
            TaxServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function setUpDatabase($app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('price');
        });
        $app['db']->connection()->getSchemaBuilder()->create('services', function (Blueprint $table) {
            $table->increments('id');
            $table->string('amount');
        });
        include_once __DIR__.'/../database/migrations/create_taxes_tables.php.stub';
        (new \CreateTaxesTables())->up();
    }
}
