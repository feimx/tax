<?php

namespace FeiMx\Tax\Taxes;

use FeiMx\Tax\Contracts\TaxContract;

class ISR extends Tax implements TaxContract
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
        // return -(($amount * 0.16) / 3) * 2;
        return $amount * $this->percentage();
    }
}
