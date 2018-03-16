<?php

namespace FeiMx\Tax\Taxes;

use FeiMx\Tax\Contracts\TaxContract;

class IEPS extends Tax implements TaxContract
{
    /**
     * Code from the SAT Catalog.
     *
     * @var string
     */
    protected $code = '003';

    /**
     * Calculate tax percentage of a given amount.
     *
     * @param int $amount Amount for aclculate
     *
     * @return float Percetage
     */
    public function calculate($amount)
    {
        return number_format($amount * $this->percentage(), 6, '.', '');
    }
}
