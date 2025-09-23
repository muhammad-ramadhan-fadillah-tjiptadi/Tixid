<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
    public function edit(Movie $movie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Movie $movie)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movie $movie)
    {
        //
    }
}
