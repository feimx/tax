<?php

namespace FeiMx\Tax\Tests;

use FeiMx\Tax\Taxes\ISR;
use FeiMx\Tax\Taxes\IVA;
use FeiMx\Tax\Taxes\IEPS;

class TaxTest extends TestCase
{
    public function testCanGetTaxName()
    {
        $iva = new IVA();
        $this->assertEquals('iva', $iva->name);
        $this->assertEquals('iva', $iva);

        $isr = new ISR();
        $this->assertEquals('isr', $isr->name);
        $this->assertEquals('isr', $isr);

        $ieps = new IEPS();
        $this->assertEquals('ieps', $ieps->name);
        $this->assertEquals('ieps', $ieps);
    }

    public function testCanGetCalculatedAmount()
    {
        $retention = true;

        $iva = new IVA();
        $this->assertEquals(16.000000, $iva->calculate(100));

        $iva = new IVA($retention);
        $this->assertEquals(-16.000000, $iva->calculate(100));

        $isr = new ISR();
        $this->assertEquals(-10.666700, $isr->calculate(100));

        $ieps = new IEPS();
        $this->assertEquals(8.000000, $ieps->calculate(100));

        $ieps = new IEPS($retention);
        $this->assertEquals(-8.000000, $ieps->calculate(100));
    }

    public function testCanGetPercentageByType()
    {
        $retention = true;

        $iva = new IVA();
        $this->assertEquals(0.16, $iva->percentage());
        $this->assertEquals(-0.16, $iva->percentage('retention'));
        $this->assertEquals(0.16, $iva->percentage('unexist'));

        $iva = new IVA($retention);
        $this->assertEquals(-0.16, $iva->percentage());

        $isr = new ISR();
        $this->assertEquals(-0.106667, $isr->percentage());

        $ieps = new IEPS();
        $this->assertEquals(0.08, $ieps->percentage());
        $this->assertEquals(0.08, $ieps->percentage('unexist'));
        $this->assertEquals(-0.08, $ieps->percentage('retention'));
        $this->assertEquals(0.11, $ieps->percentage('primary'));
        $this->assertEquals(0.13, $ieps->percentage('secondary'));

        $ieps = new IEPS($retention);
        $this->assertEquals(-0.08, $ieps->percentage());
    }
}
