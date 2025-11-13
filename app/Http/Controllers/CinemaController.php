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
        $cinemas = Cinema::query();
        //compact() -> mengirim data ke blade, nama compact sama dengan nama variable
        return view('admin.cinemas.index', compact('cinemas'));
    }

    public function datatables()
    {
        $cinemas = Cinema::query();
        // DataTables::of($movies) -> mengambil data dari query model movie, keseluruhan field
        // addColumn() -> menambahkan column yang bukan bagian dari field movies, kbiasanya digunakan untuk button atau field yang nilainya akan diolah/ manipulasi
        // addIndexColumn() -> mengambil index data, mulai dari 1
        return DataTables::of($cinemas)
        ->addIndexColumn()
        ->addColumn('action', function ($cinema) {
            $btnEdit = '<a href="' . route('admin.cinemas.edit', $cinema->id) . '" class="btn btn-primary me-2">Edit</a>';
            $btnDelete = '<form action="' . route('admin.cinemas.delete', $cinema->id) . '" method="POST">
            ' . @csrf_field() . method_field('DELETE') . ' <button type="submit" class="btn btn-danger">Hapus</button></form>';
            return '<div class="d-flex justify-content-center align-items-center gap-2">' . $btnEdit . $btnDelete . '</div>';
        })
        ->rawColumns(['action'])
        ->make(true);
        // rawColumns() -> mendaftarkan column uang baru dibuat pada addColumn()
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

    public function cinemaList()
    {
        $cinemas = Cinema::all();
        return view('schedule.cinemas', compact('cinemas'));
    }

    public function cinemaSchedules($cinema_id)
    {
        // whereHas('nama relasi', function($q) {..} : argumen 1 (nama relasi) wajibb, argumen 2 (func untuk filter pada relasi) optional)
        // whereHas('nama relasi') -> Movie::whereHas('schedules') megambil data film hanya yang memiliki relasi (memiliki data) schedules
        //  whereHas ('nama relasi', function($q) {..} -> Schedule::whereHas('movie', function($q) {$q->where('actived', 1)})) mengambil data schedule hanya yang memiliki relasi (memiliki data) movie dan nilai actived pada movienya 1
        $schedules = Schedule::where('cinema_id', $cinema_id)->with('movie')->whereHas('movie', function($q) {
            $q->where('activated', 1);
        })->get();
        return view('schedule.cinema-schedules', compact('schedules'));
    }
}
