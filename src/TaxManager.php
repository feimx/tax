<?php

namespace FeiMx\Tax;

use FeiMx\Tax\Exceptions\TaxErrorException;

class TaxManager
{
    /**
     * Amount for use when calculate taxes.
     *
     * @var int
     */
    public $amount;
    /**
     * List of taxes for calculate final amount.
     *
     * @var array
     */
    public $taxes = [];

    /**
     * @param int $amount
     */
    public function __construct($amount = 0)
    {
        $this->amount = $amount;
    }

    /**
     * @param string|FeiMx\Tax\Contracts\TaxContract $tax
     * @param bool                                   $retention
     *
     * @return mixed
     */
    public function addTax($tax, $retention = false)
    {
        if (is_string($tax)) {
            $className = $this->stringToClassName($tax);
            $tax = new $className($retention);
        }

        if (! in_array($tax, $this->taxes)) {
            $this->taxes[] = $tax;
        }

        return $this;
    }

    /**
     * @param array|\ArrayAccess
     */
    public function addTaxes(...$taxes)
    {
        if (is_array($taxes)) {
            $taxes = array_flatten($taxes);
        }

        collect($taxes)->each(function ($tax) {
            $this->addTax($tax);
        });

        return $this;
    }

    /**
     * Get total amount after taxes.
     *
     * @return float Total amount
     */
    public function total()
    {
        $total = 0;
        foreach ($this->taxes as $tax) {
            $total += $tax->calculate($this->amount);
        }

        return number_format($this->amount + $total, 6, '.', '');
    }

    /**
     * Get a list of taxes with amount calculated.
     *
     * @return float Total amount
     */
    public function get()
    {
        $taxes = array_map(function ($tax) {
            return [
                'tax' => $tax->name,
                'amount' => $tax->calculate($this->amount),
            ];
        }, $this->taxes);
        
        return array_merge([
            'amount' => $this->amount,
            'total' => $this->total,
            'taxes' => $taxes,
        ]);
    }

    /**
     * @param string $tax
     *
     * @return string $className
     */
    public function stringToClassName($tax)
    {
        $className = 'FeiMx\\Tax\\Taxes\\'.strtoupper($tax);
        if (! class_exists($className)) {
            throw new TaxErrorException("The tax '{$tax}' is not valid");
        }

        return $className;
    }

    public function __get($property)
    {
        if ('total' == $property) {
            return $this->total();
        }

        if (property_exists($this, $property)) {
            return $this->{$property};
        }

        throw new \Exception('Property not exists');
    }
}
