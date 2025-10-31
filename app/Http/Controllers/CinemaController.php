<?php

namespace App\Http\Controllers;

use App\Models\Cinema;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CinemaExport;
use Yajra\DataTables\Facades\DataTables;

class CinemaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //model::all() -> mengambil semua data di model
        $cinemas = Cinema::all();
        //compact() -> mengirim data ke blade, nama compact sama dengan nama variable
        return view('admin.cinemas.index', compact('cinemas'));
    }

    public function datatables()
    {
        $cinema = Cinema::query();
        return DataTables::of($cinema)
        ->addIndexColumn()
        ->addColumn('name', function ($cinema) {
            return $cinema->name;
        })
        ->addColumn('location', function ($cinema) {
            return $cinema->location;
        })
        ->addColumn('action', function ($cinema) {
            $btnEdit = '<a href="' . route('admin.cinemas.edit', ['id' => $cinema->id]) . '" class="btn btn-secondary">Edit</a>';
            $btnDelete = '<form action="' . route('admin.cinemas.delete', ['id' => $cinema->id]) . '" method="POST">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button class="btn btn-danger ms-3">Hapus</button>
                        </form>';
            return '<div class="d-flex justify-content-center align-items-center gap-2">' . $btnEdit . $btnDelete . '</div>';
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.cinemas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'location' => 'required|min:10',
        ], [
            'name.required' => 'Nama biokop harus di isi',
            'location.required' => 'Lokasi harus di isi',
            'location.min' => 'Lokasi harus di isi setidaknya 10 karakter'
        ]);
        $createData = Cinema::create([
            'name' => $request->name,
            'location' => $request->location,
        ]);
        if ($createData) {
            return redirect()->route('admin.cinemas.index')->with('Success', 'Berhasil membuat data baru!');
        } else {
            return redirect()->back()->with('Error', 'Gagal, silakan coba lagi');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cinema $cinema)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //edit($id) => $id dari {id} di route edit
        //Cinema::find() => mencari data di tabel cinemas berdasarkan id
        $cinema = Cinema::find($id);
        //dd() => cek data
        // dd($cinema->toArray());
        return view('admin.cinemas.edit', compact('cinema'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //(Request $request,                     <td>{{ $key + 1 }}</td>$id) : Request $request (ambil data form), $id ambil parameter placeholder {id} dari route
        $request->validate([
            'name' => 'required',
            'location' => 'required|min:10',
        ], [
            'name.required' => 'Nama bioskop wajib di isi',
            'location.required' => 'Lokasi bioskop harus di isi',
            'location.min' => 'Lokasi bioskop harus di isi minimal 10 karakter'
        ]);
        //where ('id', $id) -> sebelum di update wajib cari datanya, untuk mencari salah satunya dengan where
        //format -> where ('field'_di_fillable', $sumberData)
        $updateData = Cinema::where('id', $id)->update([
            'name' => $request->name,
            'location' => $request->location,
        ]);
        if ($updateData) {
            return redirect()->route('admin.cinemas.index')->with('Success', 'Berhasil mengubah data');
        } else {
            return redirect()->back()->with('Error', 'Gagal! silahkan coba lagi');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $schedules = Schedule::where('cinema_id', $id)->count();
        if ($schedules) {
            return redirect()->route('admin.cinemas.index')->with('error', 'Gagal! tidak dapat menghapus data bioskop! Data tertaut dengan jadwal tayang');
        }
        Cinema::where('id', $id)->delete();
        return redirect()->route('admin.cinemas.index')->with('success', 'Berhasil menghapus data!');
    }

    public function export()
    {
        // nama file yang akan di download
        $filename = 'data-bioskop.xlsx';
        return Excel::download(new \App\Exports\CinemaExport, $filename);
    }

    public function trash()
    {
        // onlyTrashed() -> filter data yang sudah dihapus, delete_at bukan null
        $cinemaTrash = Cinema::onlyTrashed('id', 'name', 'location')->get();
        return view('admin.cinemas.trash', compact('cinemaTrash'));
    }

    public function restore($id)
    {
        $cinema = Cinema::onlyTrashed()->find($id);
        // restore() -> mengembalikan data yang sudah dihapus
        $cinema->restore();
        return redirect()->route('admin.cinemas.index')->with('success', 'Berhasil mengembalikan data!');
    }

    public function deletePermanent($id)
    {
        $cinema = Cinema::onlyTrashed()->find($id);
        // forceDelete() = menghapus data secara permanen, data hilang bahkan dari db nya
        $cinema->forceDelete();
        return redirect()->back()->with('success', 'Berhasil menghapus data!');
    }
}
