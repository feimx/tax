<?php

namespace FeiMx\Tax\Tests;

use FeiMx\Tax\Traits\Taxable;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use Taxable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['amount'];

    public $timestamps = false;

    protected $table = 'services';

    /**
     * Column name used for get the price of the model.
     *
     * @return string Column name
     */
    public static function priceColumn()
    {
        return 'amount';
    }
}
