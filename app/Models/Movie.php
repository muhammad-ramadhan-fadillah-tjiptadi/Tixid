<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Movie extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'title',
        'genre',
        'duration',
        'director',
        'age_rating',
        'poster',
        'description',
        'activated',
    ];

    public function schedules() {
        return $this->hasMany(Schedule::class);
    }
}
