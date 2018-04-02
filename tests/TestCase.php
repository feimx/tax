<?php

namespace FeiMx\Tax\Tests;

use FeiMx\Tax\Models\Tax;
use FeiMx\Tax\Models\TaxGroup;
use FeiMx\Tax\TaxServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected $taxGroup;

    protected $tax;

    protected $product;

    protected $service;

    public function setup()
    {
        parent::setUp();
        $this->setUpDatabase($this->app);
        $this->product = Product::first();
        $this->service = Service::first();
        $this->tax = Tax::first();
        $this->taxGroup = TaxGroup::first();
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
            $table->decimal('price', 16, 2);
        });
        $app['db']->connection()->getSchemaBuilder()->create('services', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('amount', 16, 2);
        });
        include_once __DIR__.'/../database/migrations/create_taxes_tables.php.stub';
        (new \CreateTaxesTables())->up();

        $this->createTaxGroups();
        $this->createTaxes();
        $this->createProducts();
        $this->createServices();
    }

    public function createTaxGroups()
    {
        TaxGroup::create(['name' => 'iva']);
        TaxGroup::create(['name' => 'iva and ieps']);
        TaxGroup::create(['name' => 'ieps', 'active' => false]);
        TaxGroup::create(['name' => 'iva ret and isr']);
        TaxGroup::create(['name' => 'free iva']);
    }

    public function createTaxes()
    {
        Tax::create(['name' => 'iva']);
        Tax::create(['name' => 'iva', 'retention' => true]);
        Tax::create(['name' => 'isr']);
        Tax::create(['name' => 'ieps']);
        Tax::create(['name' => 'ieps', 'retention' => true]);
        Tax::create(['name' => 'iva', 'type' => 'free']);
    }

    public function createProducts()
    {
        Product::create(['price' => 2300.90]);
        Product::create(['price' => 100]);
        Product::create(['price' => 500]);
        Product::create(['price' => 5000]);
    }

    public function createServices()
    {
        Service::create(['amount' => 2300.90]);
        Service::create(['amount' => 100]);
        Service::create(['amount' => 2500.00]);
        Service::create(['amount' => 5000]);
    }
}
