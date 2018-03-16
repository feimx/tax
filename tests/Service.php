<?php
namespace FeiMx\Tax\Tests;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['amount'];

    public $timestamps = false;

    protected $table = 'services';
}