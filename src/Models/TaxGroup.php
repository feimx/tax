<?php

namespace FeiMx\Tax\Models;

use FeiMx\Tax\TaxManager;
use Illuminate\Database\Eloquent\Model;

class TaxGroup extends Model
{
    protected $fillable = [
        'name', 'active',
    ];

    public function taxes()
    {
        return $this->hasMany(Tax::class);
    }

    public function addTax(Tax $tax)
    {
        $this->taxes()->save($tax);
    }

    public function removeTax(Tax $tax)
    {
        $this->taxes()->find($tax->id)->delete();
    }

    public function hasTax(Tax $tax)
    {
        return (bool) $this->taxes()->find($tax->id);
    }

    public function deactivate()
    {
        $this->active = false;
        $this->save();
    }

    public function activate()
    {
        $this->active = true;
        $this->save();
    }

    public function scopeActive($query, $active = true)
    {
        return $query->whereActive($active);
    }

    public function taxManager($amount = 100)
    {
        return new TaxManager($amount);
    }
}
