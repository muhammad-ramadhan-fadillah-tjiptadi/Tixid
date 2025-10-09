<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'cinema_id',
        'movie_id',
        'hours',
        'price',
    ];

    // casts : memastikan format data
    protected function casts() : array
    {
        return [
            // mengubah format json migration hour jadi array
            'hours' => 'array'
        ];
    }

    // schedule pegang posisi kedua, panggil relasi dengan belongsTo
    // cinema pegang posisi pertamaa dan jenis (one) jadi gunakan tuggal
    public function cinema() {
        return $this->belongsTo(Cinema::class);
    }

    public function movie() {
        return $this->belongsTo(Movie::class);
    }
}
