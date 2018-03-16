<?php

namespace FeiMx\Tax\Models;

use Illuminate\Database\Eloquent\Model;

class TaxGroup extends Model
{
    protected $fillable = [
        'name', 'active',
    ];
}
