<?php

namespace FeiMx\Tax\Tests;

use FeiMx\Tax\Taxes\ISR;
use FeiMx\Tax\Taxes\IVA;
use FeiMx\Tax\Models\Tax;
use FeiMx\Tax\Taxes\IEPS;
use FeiMx\Tax\Models\TaxGroup;
use FeiMx\Tax\Contracts\TaxContract;

class TaxModelTest extends TestCase
{
    public function testCanBelongsToTaxGroup()
    {
        $tax = Tax::find(1);
        $taxGroup = TaxGroup::first();
        $this->assertNull($tax->taxGroup);

        $tax->taxGroup()->associate($taxGroup);
        $this->assertNotNull($tax->taxGroup);
    }

    public function testInfoShouldBeAnInstanceOfTaxContract()
    {
        $tax = Tax::find(1);
        $this->assertInstanceOf(TaxContract::class, $tax->info());
        $this->assertInstanceOf(TaxContract::class, $tax->info);
    }

    public function testInfoShouldBeAnInstanceOfAGivenName()
    {
        $iva = Tax::find(1);
        $isr = Tax::find(3);
        $ieps = Tax::find(4);
        $this->assertInstanceOf(IVA::class, $iva->info());
        $this->assertInstanceOf(ISR::class, $isr->info());
        $this->assertInstanceOf(IEPS::class, $ieps->info);
    }

    public function testCanFilterByRetention()
    {
        $taxes = Tax::retention()->get();
        $this->assertCount(3, $taxes);
    }

    public function testCanFilterByTralated()
    {
        $taxes = Tax::traslate()->get();
        $this->assertCount(2, $taxes);
    }
}
