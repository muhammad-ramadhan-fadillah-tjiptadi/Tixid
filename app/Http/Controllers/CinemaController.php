<?php

namespace App\Http\Controllers;

use App\Models\Cinema;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CinemaExport;

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
}
