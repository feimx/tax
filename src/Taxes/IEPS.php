<?php

namespace FeiMx\Tax\Taxes;

use FeiMx\Tax\Contracts\TaxContract;

class IEPS extends Tax implements TaxContract
{
    /**
     * Calculate tax percentage of a given amount.
     *
     * @param int $amount Amount for aclculate
     *
     * @return float Percetage
     */
    public function calculate($amount): float
    {
        return $amount * $this->percentage();
    }
}
