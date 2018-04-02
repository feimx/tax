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
        $this->assertEquals(-10.666700, $iva->calculate(100));

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

        $this->assertEquals(0.16, (new IVA())->percentage());
        $this->assertEquals(-0.106667, (new IVA('retention'))->percentage());
        $this->assertEquals(0.16, (new IVA('unexist'))->percentage());
        $this->assertEquals(0, (new IVA('free'))->percentage());

        $iva = new IVA($retention);
        $this->assertEquals(-0.106667, $iva->percentage());

        $isr = new ISR();
        $this->assertEquals(-0.106667, $isr->percentage());

        $this->assertEquals(0.08, (new IEPS())->percentage());
        $this->assertEquals(0.08, (new IEPS('unexist'))->percentage());
        $this->assertEquals(-0.08, (new IEPS('retention'))->percentage());
        $this->assertEquals(0.11, (new IEPS('primary'))->percentage());
        $this->assertEquals(0.13, (new IEPS('secondary'))->percentage());
        $this->assertEquals(0, (new IEPS('free'))->percentage());

        $ieps = new IEPS($retention);
        $this->assertEquals(-0.08, $ieps->percentage());
    }

    public function testCanGetFiscalCode()
    {
        $iva = new IVA();
        $this->assertEquals('002', $iva->code);

        $isr = new ISR();
        $this->assertEquals('001', $isr->code);

        $ieps = new IEPS();
        $this->assertEquals('003', $ieps->code);
    }
}
