<?php
namespace FeiMx\Tax\Tests;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['price'];

    public $timestamps = false;

    protected $table = 'products';
}