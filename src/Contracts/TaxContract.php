<?php

namespace FeiMx\Tax\Contracts;

interface TaxContract
{
    /**
     * Calculate tax percentage of a given amount
     * @param  int $amount Amount for aclculate
     * @return float         Percetage 
     */
    public function calculate($amount): float;
}
