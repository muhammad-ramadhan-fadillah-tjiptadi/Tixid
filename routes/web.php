<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/schedules/detail', function(){
    //standar penulisan :
    // path (mengacu ke data/fitur) gunakan jamak, folder view fitur gunakan tunggal
    return view('schedule.detail');
})->name('schedules.detail');

// Login routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [UserController::class, 'login'])->name('login.submit');

// Signup routes
// Route get untuk menampilkan data
Route::get('/signup', function () {
    return view('auth.signup');
})->name('signup');

// Route post untuk menambahkan data yang ditampilkan
// Kenapa memakai UserController karna aken mengisi tabel user
Route::post('/signup', [UserController::class, 'register'])->name('signup.send_data');
// Page untuk mengubah data
// Delete untuk menghapus data
