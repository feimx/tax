<?php

namespace FeiMx\Tax\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $fillable = [
        'name', 'type', 'retention',
    ];
}
