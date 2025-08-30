<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promo extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'promo_code',
        'discount',
        'type',
        'activated',
    ];
}
