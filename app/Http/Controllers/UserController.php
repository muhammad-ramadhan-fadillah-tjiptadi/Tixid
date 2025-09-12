<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::whereIn('role', ['admin', 'staff'])->get();
        return view('admin.users.index', compact('users'));
    }

    public function register(Request $request)
    {
        //  Request mengambil, memvalidasi, dan memanipulasi semua data dari HTTP yang masuk
        $request->validate([
            'first_name' => 'required|min:1',
            'last_name' => 'required|min:1',
            'email' => 'required|email:dns',
            'password' => 'required|min:8'
        ], [
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
        } else {
            return redirect()->route('signup')->with('failed', 'Gagal memperoleh data! Silahkan coba lagi!');
        }
    }

    public function authentication(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ], [
            'email.required' => 'Email Harus Diisi',
            'password.required' => 'Passwoord Harus Diisi'
        ]);
        // Data yang akan digunakan untuk verifikasi
        $data = $request->only(['password', 'email']);
        // Auth->attempt() -> mencocokan data (email-pw /username-pw)
        if (Auth::attempt($data)) {
            // Jika data email-pw cocok
            if (Auth::user()->role == 'admin') {
                // Dicek lagi terkait rolenya, kalo admin ke dashboard
                return redirect()->route('admin.dashboard')->with('success', 'Berhasil Login!');
            }
            return redirect()->route('home')->with('success', 'Berhasil Login!');
        } else {
            return redirect()->back()->with('error', 'Gagal! Pastikan Email dan Password Benar');
        }
    }

    public function logout()
    {
        // Logout () -> menghapus sesi login
        Auth::logout();
        return redirect()->route('home')->with('logout', 'Anda Telah Berhasil Logout! Silahkan Login Kembali Untuk Akses Lengkap');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ], [
            'name.required' => 'Nama pengguna wajib di isi',
            'email.required' => 'Email pengguna wajib di isi',
            'email.unique' => 'Email sudah pernah di gunakan',
            'email.email' => 'Email tidak valid',
        ]);
        $createData = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'staff',
            'password' => $request->password,
        ]);
        if ($createData) {
            return redirect()->route('admin.users.index')->with('Success', 'Berhasil membuat data baru!');
        } else {
            return redirect()->back()->with('Error', 'Gagal, silahkan coba lagi!');
        }
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
        $user = User::find($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email:dns',
        ], [
            'name.required' => 'Nama wajib di isi',
            'email.required' => 'Email wajib di isi',
            'email.email' => 'Email tidak valid',
            'role' => 'staff',
        ]);
        //where ('id', $id) -> sebelum di update wajib cari datanya, untuk mencari salah satunya dengan where
        //format -> where ('field'_di_fillable', $sumberData)
        $updateData = User::where('id', $id)->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        if ($updateData) {
            return redirect()->route('admin.users.index')->with('Success', 'Berhasil mengubah data');
        } else {
            return redirect()->back()->with('Error', 'Gagal! silahkan coba lagi');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        User::where('id', $id)->delete();
        return redirect()->route('admin.users.index')->with('Success', 'Berhasil menghapus data!');
    }
}
