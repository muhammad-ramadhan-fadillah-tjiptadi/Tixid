<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CinemaController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\ScheduleController;
use App\Models\Movie;

Route::get('/', [MovieController::class, 'home'])->name('home');

Route::get('/schedules/detail/{movie_id}', [MovieController::class, 'movieSchedule'])->name('movieSchedule');

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

Route::post('/auth', [UserController::class, 'authentication'])->name('auth');

Route::get('/logout', [UserController::class, 'logout'])->name('logout');
// Page untuk mengubah data
// Delete untuk menghapus data

// Untuk Halaman Admin
Route::middleware('isAdmin')->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // Cinemas
        Route::prefix('cinemas')->name('cinemas.')->group(function () {
            Route::get('/index', [CinemaController::class, 'index'])->name('index');
            Route::get('/create', [CinemaController::class, 'create'])->name('create');
            Route::post('/store', [CinemaController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [CinemaController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [CinemaController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [CinemaController::class, 'destroy'])->name('delete');
            Route::get('/export', [CinemaController::class, 'export'])->name('export');
        });

        // Film
        Route::prefix('/movies')->name('movies.')->group(function () {
            Route::get('/', [MovieController::class, 'index'])->name('index');
            Route::get('/create', [MovieController::class, 'create'])->name('create');
            Route::post('/store', [MovieController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [MovieController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [MovieController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [MovieController::class, 'destroy'])->name('delete');
            Route::patch('/patch/{id}', [MovieController::class, 'patch'])->name('patch');
            Route::get('/export', [MovieController::class, 'export'])->name('export');
        });

        // Users
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/index', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/store', [UserController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [UserController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('delete');
            Route::get('/export', [UserController::class, 'export'])->name('export');
        });
    });
});

Route::get('/movies/active', [MovieController::class, 'homeMovies'])->name('home.movies.all');

Route::middleware('isGuest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::get('/signup', function () {
        return view('auth.signup');
    })->name('signup');
});

Route::middleware('isStaff')->group(function () {
    Route::prefix('/staff')->name('staff.')->group(function () {
        Route::get('/dashboard', function () {
            return view('staff.dashboard');
        })->name('dashboard');

        // Promo
        Route::prefix('promos')->name('promos.')->group(function () {
            Route::get('/index', [PromoController::class, 'index'])->name('index');
            Route::get('/create', [PromoController::class, 'create'])->name('create');
            Route::post('/store', [PromoController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [PromoController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [PromoController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [PromoController::class, 'destroy'])->name('delete');
            Route::get('/export', [PromoController::class, 'export'])->name('export');
        });

        // Jadwal Tayang/Schedules
        Route::prefix('schedules')->name('schedules.')->group(function () {
            Route::get('/', [ScheduleController::class, 'index'])->name('index');
            Route::post('/store', [ScheduleController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [ScheduleController::class, 'edit'])->name('edit');
            Route::patch('/update/{id}', [ScheduleController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [ScheduleController::class, 'destroy'])->name('delete');
            Route::get('trash', [ScheduleController::class, 'trash'])->name('trash');
            Route::patch('/restore/{id}', [ScheduleController::class, 'restore'])->name('restore');
            Route::delete('/delete-permanent/{id}', [ScheduleController::class, 'deletePermanent'])->name('delete-permanent');
            Route::get('export', [ScheduleController::class, 'export'])->name('export');
        });
    });
});
