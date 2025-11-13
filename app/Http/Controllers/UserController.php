<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserExport;
use Yajra\DataTables\Facades\DataTables;

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

    public function datatables()
    {
        $users = User::query();
        $users = User::whereIn('role', ['admin', 'staff'])->get();
        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('name', function ($user) {
                return $user->name;
            })
            ->addColumn('email', function ($user) {
                return $user->email;
            })
            ->addColumn('role', function ($user) {
                if ($user->role === 'admin') {
                    return '<span class="badge badge-primary">Admin</span>';
                } elseif ($user->role === 'staff') {
                    return '<span class="badge badge-success">Staff</span>';
                } else {
                    return '<span class="badge badge-warning">User</span>';
                }
            })
            ->addColumn('action', function ($user) {
                $btnEdit = '<a href="' . route('admin.users.edit', ['id' => $user->id]) . '" class="btn btn-secondary">Edit</a>';
                $btnDelete = '<form action="' . route('admin.users.delete', ['id' => $user->id]) . '" method="POST">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button class="btn btn-danger ms-3">Hapus</button>
                        </form>';
                return '<div class="d-flex justify-content-center align-items-center gap-2">' . $btnEdit . $btnDelete . '</div>';
            })
            ->rawColumns(['role', 'action'])
            ->make(true);
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
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'Email Harus Diisi',
            'email.email' => 'Format Email Tidak Valid',
            'password.required' => 'Password Harus Diisi'
        ]);
        // Data yang akan digunakan untuk verifikasi
        $data = $request->only(['password', 'email']);
        // Auth->attempt() -> mencocokan data (email-pw /username-pw)
        if (Auth::attempt($data)) {
            // Jika data email-pw cocok
            if (Auth::user()->role == 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Berhasil Login!');
            } elseif (Auth::user()->role == 'staff') {
                return redirect()->route('staff.dashboard')->with('success', 'Berhasil Login!');
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
            'role' => 'required|in:admin,staff',
        ], [
            'name.required' => 'Nama pengguna wajib di isi',
            'email.required' => 'Email pengguna wajib di isi',
            'email.unique' => 'Email sudah pernah di gunakan',
            'email.email' => 'Email tidak valid',
            'role.required' => 'Role wajib dipilih',
            'role.in' => 'Role harus admin atau staff',
        ]);
        $createData = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);
        if ($createData) {
            return redirect()->route('admin.users.index')->with('success', 'Berhasil membuat data baru!');
        } else {
            return redirect()->back()->with('error', 'Gagal, silahkan coba lagi!');
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
        $user = User::findOrFail($id);
        if ($user->delete()) {
            return redirect()->route('admin.users.index')->with('success', 'Berhasil Menghapus Data!');
        }
        return redirect()->back()->with('error', 'Gagal menghapus data. Silakan coba lagi.');
    }

    public function export()
    {
        // nama file yang akan di download
        $filename = 'data-user.xlsx';
        return Excel::download(new UserExport, $filename);
    }

    public function trash()
    {
        $userTrash = User::onlyTrashed('id', 'name', 'email', 'role')->get();
        return view('admin.users.trash', compact('userTrash'));
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->find($id);
        // restore() -> mengembalikan data yang sudah dihapus
        $user->restore();
        return redirect()->route('admin.users.index')->with('success', 'Berhasil mengembalikan data!');
    }

    public function deletePermanent($id)
    {
        $user = User::onlyTrashed()->find($id);
        // forceDelete() = menghapus data secara permanen, data hilang bahkan dari db nya
        $user->forceDelete();
        return redirect()->back()->with('success', 'Berhasil menghapus data!');
    }
}
