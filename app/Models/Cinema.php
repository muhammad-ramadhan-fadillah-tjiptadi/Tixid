<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cinema extends Model
{
    // mendaftarkan softDeletes
    use SoftDeletes;

    // mendaftarkan column yang akan diisi oleh pengguna (column migration selain id dan timestamps)
    protected $fillable = [
        'name',
        'location',
    ];
}
