<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function register(Request $request)
    {
        //  Request mengambil, memvalidasi, dan memanipulasi semua data dari HTTP yang masuk
        $request->validate([
            'first_name' => 'required|min:1',
            'last_name' => 'required|min:1',
            'email' => 'required|email:dns',
            'password' => 'required|min:8'
        ],[
            'first_name.required' => 'First name wajib di isi',
            'first_name.min' => 'First name minimal 1',
            'last_name.required' => 'Last name wajib di isi',
            'last_name.min' => 'Last name minimal 1',
            'email.required' => 'Email wajib di isi',
            'email.email' => 'Email tidak valid',
            'password.required' => 'Password wajib di isi',
            'password.min' => 'Password minimal 8 karakter',
        ]);

        // User::create bagian create adalah eloquent
        $createData = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            // Hash::make($request->password) adalah untuk enkripsi password
            'password' => Hash::make($request->password),
            'role' => 'user'
        ]);

        if ($createData) {
            // redirect untuk mengarahkan ke route, with adalah untuk memberikan pesan
            return redirect()->route('login')->with('success', 'Berhasil membuat akun! Silahkan login!');
        }else {
            return redirect()->route('signup')->with('failed', 'Gagal memperoleh data! Silahkan coba lagi!');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
