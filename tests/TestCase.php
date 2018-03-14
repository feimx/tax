<?php

namespace FeiMx\Tax\Tests;

use FeiMx\Tax\TaxServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            TaxServiceProvider::class,
        ];
    }
}
