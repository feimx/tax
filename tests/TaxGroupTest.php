<?php

namespace FeiMx\Tax\Tests;

use FeiMx\Tax\Models\Tax;
use FeiMx\Tax\Models\TaxGroup;
use FeiMx\Tax\TaxManager;

class TaxGroupTest extends TestCase
{
    public function testTaxGroupCanHaveTaxes()
    {
        $taxGroup = TaxGroup::first();
        $taxGroup->taxes()->save(Tax::find(1));
        $taxGroup->addTax(Tax::find(2));

        $this->assertCount(2, $taxGroup->taxes);
    }

    public function testTaxGroupCanRemoveTaxes()
    {
        $taxGroup = TaxGroup::first();
        $taxGroup->taxes()->save($tax = Tax::find(1));

        $taxGroup->removeTax($tax);
        $this->assertCount(0, $taxGroup->taxes);
    }

    public function testFilterByActive()
    {
        $taxGroups = TaxGroup::active()->get();
        $this->assertCount(3, $taxGroups);

        $taxGroups = TaxGroup::active(false)->get();
        $this->assertCount(1, $taxGroups);
    }

    public function testDetermineIfAlreadyHasATax()
    {
        $taxGroup = TaxGroup::first();
        $tax = Tax::first();
        $this->assertFalse($taxGroup->hasTax($tax));

        $taxGroup->addTax($tax);
        $this->assertTrue($taxGroup->hasTax($tax));
    }

    public function testCanActivateAndDeactivate()
    {
        $taxGroup = TaxGroup::first();
        $taxGroup->deactivate();
        $this->assertFalse($taxGroup->active);

        $taxGroup->activate();
        $this->assertTrue($taxGroup->active);
    }

    public function testCanGetATaxManagerInstance()
    {
        $taxGroup = TaxGroup::find(1);
        $this->assertInstanceOf(TaxManager::class, $taxGroup->taxManager());
    }
}
