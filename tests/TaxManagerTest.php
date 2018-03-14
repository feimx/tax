<?php

namespace FeiMx\Tax\Tests;

use FeiMx\Tax\Contracts\TaxContract;
use FeiMx\Tax\Exceptions\TaxErrorException;
use FeiMx\Tax\TaxManager;
use FeiMx\Tax\Taxes\IVA;

class TaxManagerTest extends TestCase
{
    public function testInstanceOfTaxManager()
    {
        $taxManager = new TaxManager(100);
        $this->assertInstanceOf(TaxManager::class, $taxManager);
    }

    public function testCanSeeOriginalAmount()
    {
        $taxManager = new TaxManager(100);
        $this->assertEquals(100, $taxManager->amount);
    }

    public function testStringShoulBeConvertedToValidClassName()
    {
        $tax = 'iva';
        $taxManager = new TaxManager(100);
        $this->assertEquals(IVA::class, $taxManager->stringToClassName($tax));
    }

    public function testCanAddTaxes()
    {
        $taxManager = new TaxManager(100);
        $taxManager->addTax('iva');
        $taxManager->addTax('isr');
        $taxManager->addTax('ieps');
        $this->assertCount(3, $taxManager->taxes);
    }

    public function testCanAddMultipleTaxesUsingMultipleParams()
    {
        $taxManager = new TaxManager(100);
        $taxManager->addTaxes('iva', 'isr', 'ieps');
        $this->assertCount(3, $taxManager->taxes);
    }

    public function testCanAddMultipleTaxesUsingArray()
    {
        $taxManager = new TaxManager(100);
        $taxManager->addTaxes(['iva', 'isr', 'ieps']);
        $this->assertCount(3, $taxManager->taxes);
    }

    public function testCanAddMultipleTaxesUsingCollection()
    {
        $taxManager = new TaxManager(100);
        $taxManager->addTaxes(collect(['iva', 'isr', 'ieps']));
        $this->assertCount(3, $taxManager->taxes);
    }

    public function testCanOnlyAddValidTaxes()
    {
        $this->expectException(TaxErrorException::class);
        $taxManager = new TaxManager(100);
        $taxManager->addTax('iva');
        $taxManager->addTax('local');
        $this->assertCount(1, $taxManager->taxes);
    }

    public function testTaxesShouldBeAnInstanceOfTaxContractor()
    {
        $taxManager = new TaxManager(100);
        $taxManager->addTax('iva');
        $taxManager->addTax('isr');
        $taxManager->addTax('IEPS');

        foreach ($taxManager->taxes as $tax) {
            $this->assertInstanceOf(TaxContract::class, $tax);
        }
    }

    public function testCanAddMultipleTaxWithoutAddingDuplicateEntries()
    {
        $taxManager = new TaxManager(100);
        $taxManager->addTax('iva');
        $taxManager->addTax('isr');
        $taxManager->addTax('isr');
        $this->assertCount(2, $taxManager->taxes);
    }

    public function testCanCalculateIVA()
    {
        $taxManager = new TaxManager(100);
        $taxManager->addTax('iva');
        $this->assertEquals(116.000000, $taxManager->get());

        $taxManager = new TaxManager(5470);
        $taxManager->addTax('iva');
        $this->assertEquals(6345.200000, $taxManager->get());

        $taxManager = new TaxManager(2300.90);
        $taxManager->addTax('iva');
        $this->assertEquals(2669.044000, $taxManager->get());
    }

    public function testCanCalculateIVARetention()
    {
        $retention = true;

        $taxManager = new TaxManager(100);
        $taxManager->addTax('iva', $retention);
        $this->assertEquals(84.000000, $taxManager->get());

        $taxManager = new TaxManager(5470);
        $taxManager->addTax('iva', $retention);
        $this->assertEquals(4594.800000, $taxManager->get());

        $taxManager = new TaxManager(2300.90);
        $taxManager->addTax('iva', $retention);
        $this->assertEquals(1932.756000, $taxManager->get());
    }

    public function testCanCalculateIEPS()
    {
        $taxManager = new TaxManager(100);
        $taxManager->addTax('ieps');
        $this->assertEquals(108.000000, $taxManager->get());

        $taxManager = new TaxManager(5470);
        $taxManager->addTax('ieps');
        $this->assertEquals(5907.600000, $taxManager->get());

        $taxManager = new TaxManager(2300.90);
        $taxManager->addTax('ieps');
        $this->assertEquals(2484.972000, $taxManager->get());
    }

    public function testCanCalculateIEPSRetention()
    {
        $retention = true;

        $taxManager = new TaxManager(100);
        $taxManager->addTax('ieps', $retention);
        $this->assertEquals(92.000000, $taxManager->get());

        $taxManager = new TaxManager(5470);
        $taxManager->addTax('ieps', $retention);
        $this->assertEquals(5032.400000, $taxManager->get());

        $taxManager = new TaxManager(2300.90);
        $taxManager->addTax('ieps', $retention);
        $this->assertEquals(2116.828000, $taxManager->get());
    }

    public function testCanCalculateISR()
    {
        $taxManager = new TaxManager(100);
        $taxManager->addTax('isr');
        $this->assertEquals(89.333300, $taxManager->get());

        $taxManager = new TaxManager(5470);
        $taxManager->addTax('isr');
        $this->assertEquals(4886.531510, $taxManager->get());

        $taxManager = new TaxManager(2300.90);
        $taxManager->addTax('isr');
        $this->assertEquals(2055.469900, $taxManager->get());
    }

    public function testCanCalculateMultipleTaxes()
    {
        // 2300.90 + (2300.90*.16) + (2300.90*-0.106667)
        $taxManager = new TaxManager(100);
        $taxManager->addTax('iva');
        $taxManager->addTax('isr');
        $this->assertEquals(105.333300, $taxManager->get());

        $taxManager = new TaxManager(5470);
        $taxManager->addTax('iva');
        $taxManager->addTax('isr');
        $this->assertEquals(5761.731510, $taxManager->get());

        $taxManager = new TaxManager(2300.90);
        $taxManager->addTax('iva');
        $taxManager->addTax('isr');
        $this->assertEquals(2423.613900, $taxManager->get());

        // 2300.90 + (2300.90*.16) + (2300.90*-0.106667) + (2300.90*0.08)
        $taxManager = new TaxManager(100);
        $taxManager->addTax('iva');
        $taxManager->addTax('isr');
        $taxManager->addTax('ieps');
        $this->assertEquals(113.333300, $taxManager->get());

        $taxManager = new TaxManager(5470);
        $taxManager->addTax('iva');
        $taxManager->addTax('ieps');
        $taxManager->addTax('isr');
        $this->assertEquals(6199.331510, $taxManager->get());

        $taxManager = new TaxManager(2300.90);
        $taxManager->addTax('iva');
        $taxManager->addTax('isr');
        $taxManager->addTax('ieps');
        $this->assertEquals(2607.685900, $taxManager->get());
    }
}
