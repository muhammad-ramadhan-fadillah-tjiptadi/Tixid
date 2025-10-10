<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MovieExport;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // all : ambil data tanpa filter
        $movies = Movie::all();
        return view('admin.movie.index', compact('movies'));
    }

    public function home()
    {
        // where('field', 'operator', 'value') : mencari data
        // operator : = / < / <= / > / >= / <> / !=
        // ASC : a-z, 0-9, terlama-terbaru, DESC : 9-0, z-a, terbaru ke terlama
        // limit(angka) : mengambil hanya beberapa data
        // get() : ambil hasil proses filter
        $movies = Movie::where('activated', 1)->orderBy('created_at', 'DESC')->limit(3)->get();
        return view('home', compact('movies'));
    }

    public function homeMovies()
    {
        $movies = Movie::where('activated', 1)->orderBy('created_at', 'DESC')->get();
        return view('movies', compact('movies'));
    }

    public function movieSchedule($movie_id)
    {
        // ambil data movie bersama schedule dan cinema
        // karna cinema adanya relasi dengan schedule bukan movie, jadi gunakan schedule.cinema
        $movie = Movie::where('id', $movie_id)->with('schedules', 'schedules.cinema')->first();
        // schedules: mengambil relasi schedules
        // schedules.cinema: ambil relasi cinema dari schedules
        // first () : karna mau ambil 1 film
        return view('schedule.detail', compact('movie'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.movie.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'title' => 'required',
            'duration' => 'required',
            'genre' => 'required',
            'director' => 'required',
            'age_rating' => 'required',
            'description' => 'required|min:10',
            // mimes : memastikan ekstensi (jenis file) yang diupload
            'poster' => 'required|mimes:jpeg,png,jpg,svg,webp',
        ], [
            'title.required' => 'Judul film wajib diisi',
            'duration.required' => 'Durasi film wajib diisi',
            'genre.required' => 'Genre film wajib diisi',
            'director.required' => 'Sutradara film wajib diisi',
            'age_rating.required' => 'Usia minimal film wajib diisi',
            'description.required' => 'Sinopsis film wajib diisi',
            'description.min' => 'Sinopsis film minimal 10 karakter',
            'poster.required' => 'Poster film wajib diisi',
            'poster.mimes' => 'Poster film harus berekstensi jpeg, png, jpg, svg, atau webp',
        ]);
        //  ambil file dari input : $request->file('name_input')
        $poster = $request->file('poster');
        // buat namaa baru untuk filenya
        // format file yang diharapkan : <acak>-poser.jpg
        // getClientOriginalExtension() : mengambil eksternal file yang diupload
        $namafile = Str::random(10) . "-poster." . $poster->getClientOriginalExtension();
        // simpan file ke folder storage : storeAs("namasubfolder", "namafile", visibility)
        $path = $poster->storeAs("poster", $namafile, "public");
        $createData = Movie::create([
            'title' => $request->title,
            'duration' => $request->duration,
            'genre' => $request->genre,
            'director' => $request->director,
            'age_rating' => $request->age_rating,
            'description' => $request->description,
            // poster diisi dengan hasil storeAs(), hasil penyimpanan file di storage sebelumnya
            'poster' => $path,
            'activated' => 1
        ]);
        if ($createData) {
            return redirect()->route('admin.movies.index')->with('success', 'Berhasil membuat data baru!');
        } else {
            return redirect()->back()->with('error', 'Gagal, silakan coba lagi');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Movie $movie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Movie $movie, $id)
    {
        $movie = Movie::find($id);
        return view('admin.movie.edit', compact('movie'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Movie $movie, $id)
    {
        $request->validate([
            'title' => 'required',
            'duration' => 'required',
            'genre' => 'required',
            'director' => 'required',
            'age_rating' => 'required',
            'description' => 'required',
            'poster' => 'mimes:jpg,jpeg,png,svg,webp'
        ], [
            'title.required' => 'Judul film wajib diisi',
            'duration.required' => 'Durasi film wajib diisi',
            'genre.required' => 'Genre film wajib diisi',
            'director.required' => 'Sutradara film wajib diisi',
            'age_rating.required' => 'Usia minimal film wajib diisi',
            'description.required' => 'Sinopsis film wajib diisi',
            'description.min' => 'Sinopsis harus diisi minimal 10 karakter',
            'poster.mimes' => 'Poster harus berupa jpg, jpeg, png, svg, atau webp'
        ]);
        // ambil data sebelumnya
        $movie = Movie::find($id);
        // jika input file poster disini
        if ($request->hasFile('poster')) {
            $filePath = storage_path('app/public/' . $movie->poster);
            // jika file ada di storage path tersebut
            if (file_exists($filePath)) {
                // hapus file lama
                unlink($filePath);
            }
            $file = $request->file('poster');
            // buat nama baru untuk file
            $filename = 'poster-' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs("poster", $filename, "public");
            $movie->poster = $path;
        }
        $updateData = $movie->update([
            'title' => $request->title,
            'duration' => $request->duration,
            'genre' => $request->genre,
            'director' => $request->director,
            'age_rating' => $request->age_rating,
            'description' => $request->description,
            'poster' => $request->hasFile('poster') ? $path : $movie->poster,
            'activated' => 1
        ]);
        if ($updateData) {
            return redirect()->route('admin.movies.index')->with('success', 'Berhasil mengubah data!');
        } else {
            return redirect()->back()->with('error', 'Gagal, silakan coba lagi');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movie $movie, $id)
    {
        $schedules = Schedule::where('movie_id', $id)->count();
        if ($schedules) {
            return redirect()->route('admin.movies.index')->with('error', 'Gagal! tidak dapat menghapus data bioskop! Data tertaut dengan jadwal tayang');
        }

        $movie = Movie::findOrFail($id);

        // Soft delete - jangan hapus file poster, biarkan tetap ada untuk data sampah
        // File poster akan dihapus saat deletePermanent dipanggil
        $movie->delete();

        return redirect()->route('admin.movies.index')
            ->with('success', 'Berhasil menghapus data!');
    }

    public function patch($id)
    {
        $movie = Movie::findOrFail($id);

        // Toggle activation status
        $movie->update(['activated' => $movie->activated ? 0 : 1]);

        $status = $movie->activated ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.movies.index')
            ->with('success', "Film berhasil $status");
    }

    public function export()
    {
        // nama file yang akaan di download
        $filename = "data.film.xlsx";
        return Excel::download(new MovieExport(), $filename);
    }

    public function trash()
    {
        $movieTrash = Movie::onlyTrashed()->get();
        return view('admin.movie.trash', compact('movieTrash'));
    }

    public function restore($id)
    {
        $movie= Movie::onlyTrashed()->find($id);
        // restore() -> mengembalikan data yang sudah dihapus
        $movie->restore();
        return redirect()->route('admin.movies.index')->with('success', 'Berhasil mengembalikan data!');
    }

    public function deletePermanent($id)
    {
        $movie = Movie::onlyTrashed()->find($id);
        
        if ($movie) {
            // Hapus file poster dari storage
            $filePath = storage_path('app/public/' . $movie->poster);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            
            // forceDelete() = menghapus data secara permanen, data hilang bahkan dari db nya
            $movie->forceDelete();
            
            return redirect()->back()->with('success', 'Berhasil menghapus data secara permanen!');
        }
        
        return redirect()->back()->with('error', 'Data tidak ditemukan!');
    }
}
