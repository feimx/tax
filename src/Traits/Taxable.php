<?php

namespace FeiMx\Tax\Traits;

use FeiMx\Tax\Exceptions\TaxErrorException;
use FeiMx\Tax\Models\TaxGroup;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait Taxable
{
    /**
     * Column name used for get the price of the model.
     *
     * @return string Column name
     */
    public static function priceColumn()
    {
        return 'price';
    }

    /**
     * [taxGroups description].
     *
     * @return [type] [description]
     */
    public function taxGroups(): MorphToMany
    {
        return $this->morphToMany(
            TaxGroup::class,
            'model',
            'model_has_tax_groups',
            'model_id',
            'tax_group_id'
        );
    }

    public function hasTaxGroups()
    {
        return (bool) $this->taxGroups->count();
    }

    /**
     * Determine if the model has (one of) the given tax group(s).
     *
     * @param string|array|\FeiMx\Tax\Models\TaxGroup|\Illuminate\Support\Collection $taxGroups
     *
     * @return bool
     */
    public function hasTaxGroup($taxGroups)
    {
        if (is_string($taxGroups)) {
            return $this->taxGroups->contains('name', $taxGroups);
        }
        if ($taxGroups instanceof TaxGroup) {
            return $this->taxGroups->contains('id', $taxGroups->id);
        }
        if (is_array($taxGroups)) {
            foreach ($taxGroups as $taxGroup) {
                if ($this->hasTaxGroup($taxGroup)) {
                    return true;
                }
            }

            return false;
        }

        return $taxGroups->intersect($this->taxGroups)->isNotEmpty();
    }

    /**
     * Assign the given taxGroup to the model.
     *
     * @param array|string|\FeiMx\Tax\Models\TaxGroup ...$taxGroups
     *
     * @return $this
     */
    public function assignTaxGroup(...$taxGroups)
    {
        if (0 == count($taxGroups)) {
            throw new TaxErrorException('You must pass a valid TaxGroup');
        }

        $taxGroups = collect($taxGroups)
            ->flatten()
            ->map(function ($taxGroup) {
                return $this->getStoredTaxGroup($taxGroup);
            })
            ->all();

        $this->taxGroups()->saveMany($taxGroups);

        return $this;
    }

    /**
     * Revoke the given role from the model.
     *
     * @param string|\FeiMx\Tax\Models\TaxGroup $taxGroup
     */
    public function removeTaxGroup($taxGroup)
    {
        $this->taxGroups()->detach($this->getStoredTaxGroup($taxGroup));
    }

    /**
     * Remove all current tax groups and set the given ones.
     *
     * @param array|\FeiMx\Tax\Models\TaxGroup|string ...$taxGroups
     *
     * @return $this
     */
    public function syncTaxGroups(...$taxGroups)
    {
        $this->taxGroups()->detach();

        return $this->assignTaxGroup($taxGroups);
    }

    /**
     * Get total amount for current TaxGroup.
     *
     * @param \FeiMx\Tax\Models\TaxGroup|string $taxGroup
     *
     * @return $total
     */
    public function total($taxGroup = null)
    {
        if (null === $taxGroup) {
            throw new TaxErrorException('You must pass a valid TaxGroup');
        }

        $taxGroup = $this->getStoredTaxGroup($taxGroup);
        $column = self::priceColumn();

        return $total = $taxGroup->taxManager($this->{$column})
            ->addTaxes(
                $taxGroup->taxes->map(function ($tax) {
                    return $tax->info();
                })
                ->all()
            )
            ->total();
    }

    /**
     * Get a list of taxes with amount calculated for the given TaxGroup.
     *
     * @param \FeiMx\Tax\Models\TaxGroup|string $taxGroup
     *
     * @return $total
     */
    public function getAmounts($taxGroup = null)
    {
        if (null === $taxGroup) {
            throw new TaxErrorException('You must pass a valid TaxGroup');
        }

        $taxGroup = $this->getStoredTaxGroup($taxGroup);
        $column = self::priceColumn();

        return $taxGroup->taxManager($this->{$column})
            ->addTaxes(
                $taxGroup->taxes->map(function ($tax) {
                    return $tax->info();
                })
                ->all()
            )
            ->get();
    }

    protected function getStoredTaxGroup($taxGroup): TaxGroup
    {
        if (is_numeric($taxGroup)) {
            return TaxGroup::find($taxGroup);
        }
        if (is_string($taxGroup)) {
            return TaxGroup::whereName($taxGroup)->first();
        }

        return $taxGroup;
    }
}
