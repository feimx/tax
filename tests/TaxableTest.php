<?php

namespace FeiMx\Tax\Tests;

use FeiMx\Tax\Models\TaxGroup;
use FeiMx\Tax\Exceptions\TaxErrorException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaxableTest extends TestCase
{
    use RefreshDatabase;

    public function testCanDeterminateIfHasTaxGroups()
    {
        $this->assertCount(0, $this->product->taxGroups);
        $this->assertfalse($this->product->hasTaxGroups());
    }

    public function testCanDeterminateIfHasATaxGroup()
    {
        $this->assertfalse($this->product->hasTaxGroup($this->taxGroup->name));
        $this->assertfalse($this->product->hasTaxGroup($this->taxGroup));
        $this->assertfalse($this->product->hasTaxGroup(
            [$this->taxGroup, TaxGroup::find(2), TaxGroup::find(3)->name]
        ));
        $this->assertfalse($this->product->hasTaxGroup(
            collect([$this->taxGroup, TaxGroup::find(2), TaxGroup::find(3)->name])
        ));
    }

    public function testCanAddAndRemoveATaxGroup()
    {
        $this->product->assignTaxGroup('iva');
        $this->assertTrue($this->product->hasTaxGroup('iva'));

        $this->product->removeTaxGroup('iva');
        $this->assertFalse($this->product->fresh()->hasTaxGroup('iva'));
    }

    public function testCanAssignATaxGroupUsingObject()
    {
        $this->product->assignTaxGroup($this->taxGroup);
        $this->assertTrue($this->product->hasTaxGroup($this->taxGroup));
    }

    public function testCanAssignATaxGroupUsingId()
    {
        $this->product->assignTaxGroup($this->taxGroup->id);
        $this->assertTrue($this->product->hasTaxGroup($this->taxGroup));
    }

    public function testCanAddMultipleTaxGroupsAtOnce()
    {
        $this->product->assignTaxGroup('iva and ieps', 'iva');
        $this->assertTrue($this->product->fresh()->hasTaxGroup('iva and ieps'));
        $this->assertTrue($this->product->fresh()->hasTaxGroup('iva'));
    }

    public function testCanAddMultipleTaxGroupsUsingAnArray()
    {
        $this->product->assignTaxGroup(['iva and ieps', 'iva']);
        $this->assertTrue($this->product->fresh()->hasTaxGroup('iva and ieps'));
        $this->assertTrue($this->product->fresh()->hasTaxGroup('iva'));
    }

    public function testCanAddMultipleTaxGroupsUsingACollection()
    {
        $this->product->assignTaxGroup(collect(['iva and ieps', 'iva']));
        $this->assertTrue($this->product->fresh()->hasTaxGroup('iva and ieps'));
        $this->assertTrue($this->product->fresh()->hasTaxGroup('iva'));
    }

    public function testShouldThrownAnExceptionIfNotPassAnAssignTaxGroup()
    {
        $this->expectException(TaxErrorException::class);
        $this->product->assignTaxGroup();
    }

    public function testCanSyncTaxGroupsFromString()
    {
        $this->product->assignTaxGroup('iva');
        $this->product->syncTaxGroups('iva and ieps');
        $this->assertFalse($this->product->hasTaxGroup('iva'));
        $this->assertTrue($this->product->hasTaxGroup('iva and ieps'));
    }

    public function testCanSyncMultipleTaxGroupsAtOnce()
    {
        $this->product->assignTaxGroup('iva');
        $this->product->syncTaxGroups('iva and ieps', 'iva');
        $this->assertTrue($this->product->hasTaxGroup('iva'));
        $this->assertTrue($this->product->hasTaxGroup('iva and ieps'));
    }

    public function testCanSyncMultipleTaxGroupsAtOnceUsingArray()
    {
        $this->product->assignTaxGroup('iva');
        $this->product->syncTaxGroups(['iva and ieps', 'iva']);
        $this->assertTrue($this->product->hasTaxGroup('iva'));
        $this->assertTrue($this->product->hasTaxGroup('iva and ieps'));
    }

    public function testWillRemoveAllTaxGroupsWhenPassAnEmptyArray()
    {
        $this->product->assignTaxGroup('iva', 'iva and ieps');
        $this->product->syncTaxGroups([]);
        $this->assertFalse($this->product->hasTaxGroup('iva'));
        $this->assertFalse($this->product->hasTaxGroup('iva and ieps'));
    }

    public function testCanDeterminateColumnForCalculateAmount()
    {
        $this->assertEquals('price', Product::priceColumn());
        $this->assertEquals('amount', Service::priceColumn());
    }

    public function testCanGetCalculatedAmount()
    {
        $this->taxGroup->addTax($this->tax);
        $this->product->assignTaxGroup($this->taxGroup);
        $this->assertEquals(2669.044000, $this->product->total($this->taxGroup));
    }

    public function testShouldThrownAnExceptionIfNotPassATaxGroup()
    {
        $this->expectException(TaxErrorException::class);
        $this->product->assignTaxGroup($this->taxGroup);
        $this->product->total();
    }

    public function testCanGetAListOfCalculatedAmounts()
    {
        $this->taxGroup->addTax($this->tax);
        $this->product->assignTaxGroup($this->taxGroup);
        $data = [
            'amount' => '2300.9',
            'total' => '2669.044000',
            'taxes' => [
                [
                    'tax' => 'iva',
                    'amount' => '368.144000',
                ],
            ],
        ];

        $this->assertSame($data, $this->product->getAmounts($this->taxGroup));
    }
}
