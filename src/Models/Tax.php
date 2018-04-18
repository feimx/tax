<?php

namespace FeiMx\Tax\Models;

use FeiMx\Tax\Contracts\TaxContract;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $fillable = [
        'name', 'type', 'retention',
    ];

    protected $casts = [
        'retention' => 'boolean',
    ];

    public function taxGroup()
    {
        return $this->belongsTo(TaxGroup::class);
    }

    public function info(): TaxContract
    {
        $className = '\\FeiMx\\Tax\\Taxes\\'.strtoupper($this->name);

        return new $className($this->retention ? $this->retention : $this->type);
    }

    public function getInfoAttribute(): TaxContract
    {
        return $this->info();
    }

    public function scopeRetention($query)
    {
        return $query->whereRetention(true);
    }

    public function scopeTraslate($query)
    {
        return $query->whereRetention(false);
    }
}
