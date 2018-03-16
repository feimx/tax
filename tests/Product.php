<?php

namespace FeiMx\Tax\Tests;

use FeiMx\Tax\Traits\Taxable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Taxable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['price'];

    public $timestamps = false;

    protected $table = 'products';
}
