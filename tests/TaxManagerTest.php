<?php

namespace FeiMx\Tax\Tests;

use FeiMx\Tax\Taxes\IVA;
use FeiMx\Tax\TaxManager;
use FeiMx\Tax\Contracts\TaxContract;
use FeiMx\Tax\Exceptions\TaxErrorException;

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
        $this->assertEquals(116.000000, $taxManager->total());

        $taxManager = new TaxManager(5470);
        $taxManager->addTax('iva');
        $this->assertEquals(6345.200000, $taxManager->total());

        $taxManager = new TaxManager(2300.90);
        $taxManager->addTax('iva');
        $this->assertEquals(2669.044000, $taxManager->total());
    }

    public function testCanCalculateIVARetention()
    {
        $retention = true;

        $taxManager = new TaxManager(100);
        $taxManager->addTax('iva', $retention);
        $this->assertEquals(84.000000, $taxManager->total());

        $taxManager = new TaxManager(5470);
        $taxManager->addTax('iva', $retention);
        $this->assertEquals(4594.800000, $taxManager->total());

        $taxManager = new TaxManager(2300.90);
        $taxManager->addTax('iva', $retention);
        $this->assertEquals(1932.756000, $taxManager->total());
    }

    public function testCanCalculateIEPS()
    {
        $taxManager = new TaxManager(100);
        $taxManager->addTax('ieps');
        $this->assertEquals(108.000000, $taxManager->total());

        $taxManager = new TaxManager(5470);
        $taxManager->addTax('ieps');
        $this->assertEquals(5907.600000, $taxManager->total());

        $taxManager = new TaxManager(2300.90);
        $taxManager->addTax('ieps');
        $this->assertEquals(2484.972000, $taxManager->total());
    }

    public function testCanCalculateIEPSRetention()
    {
        $retention = true;

        $taxManager = new TaxManager(100);
        $taxManager->addTax('ieps', $retention);
        $this->assertEquals(92.000000, $taxManager->total());

        $taxManager = new TaxManager(5470);
        $taxManager->addTax('ieps', $retention);
        $this->assertEquals(5032.400000, $taxManager->total());

        $taxManager = new TaxManager(2300.90);
        $taxManager->addTax('ieps', $retention);
        $this->assertEquals(2116.828000, $taxManager->total());
    }

    public function testCanCalculateISR()
    {
        $taxManager = new TaxManager(100);
        $taxManager->addTax('isr');
        $this->assertEquals(89.333300, $taxManager->total());

        $taxManager = new TaxManager(5470);
        $taxManager->addTax('isr');
        $this->assertEquals(4886.531510, $taxManager->total());

        $taxManager = new TaxManager(2300.90);
        $taxManager->addTax('isr');
        $this->assertEquals(2055.469900, $taxManager->total());
    }

    public function testCanCalculateMultipleTaxes()
    {
        $taxManager = new TaxManager(100);
        $taxManager->addTax('iva');
        $taxManager->addTax('isr');
        $this->assertEquals(105.333300, $taxManager->total());

        $taxManager = new TaxManager(5470);
        $taxManager->addTax('iva');
        $taxManager->addTax('isr');
        $this->assertEquals(5761.731510, $taxManager->total());

        $taxManager = new TaxManager(2300.90);
        $taxManager->addTax('iva');
        $taxManager->addTax('isr');
        $this->assertEquals(2423.613900, $taxManager->total());

        $taxManager = new TaxManager(100);
        $taxManager->addTax('iva');
        $taxManager->addTax('isr');
        $taxManager->addTax('ieps');
        $this->assertEquals(113.333300, $taxManager->total());

        $taxManager = new TaxManager(5470);
        $taxManager->addTax('iva');
        $taxManager->addTax('ieps');
        $taxManager->addTax('isr');
        $this->assertEquals(6199.331510, $taxManager->total());

        $taxManager = new TaxManager(2300.90);
        $taxManager->addTax('iva');
        $taxManager->addTax('isr');
        $taxManager->addTax('ieps');
        $this->assertEquals(2607.685900, $taxManager->total());
    }

    public function testGetTotalByProperty()
    {
        $taxManager = new TaxManager(100);
        $taxManager->addTax('iva');
        $this->assertEquals(116.000000, $taxManager->total);
    }

    public function testGetAListOfTaxesCalculated()
    {
        $taxManager = new TaxManager(100);
        $taxManager->addTax('iva');
        $taxManager->addTax('isr');
        $data = [
            'amount' => 100,
            'total' => '105.333300',
            'taxes' => [
                [
                    'tax' => 'iva',
                    'amount' => '16.000000',
                ],
                [
                    'tax' => 'isr',
                    'amount' => '-10.666700',
                ],
            ],
        ];
        $this->assertSame($data, $taxManager->get());
    }

    public function testCombineTraslateAndRetentions()
    {
        $retention = true;

        $taxManager = new TaxManager(100);
        $taxManager->addTax('iva');
        $taxManager->addTax('isr');
        $taxManager->addTax('iva', $retention);
        $taxManager->addTax('ieps', $retention);
        $this->assertEquals(81.333300, $taxManager->total());
    }
}
