<?php

namespace App\Http\Controllers;

use App\Exports\ScheduleExport;
use App\Models\Cinema;
use App\Models\Movie;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cinemas = Cinema::all();
        $movies = Movie::all();
        // with() : memanggil detail relasi, tidak hanya angka idnya
        // isi with () dari function rlasi di model
        $schedules = Schedule::with(['cinema', 'movie'])->get();
        return view('staff.schedule.index', compact('cinemas', 'movies', 'schedules'));
    }

    public function datatables()
    {
        $schedules = Schedule::with(['cinema', 'movie']);
        
        return DataTables::of($schedules)
            ->addIndexColumn()
            ->addColumn('cinema_name', function($schedule) {
                return $schedule->cinema->name ?? '-';
            })
            ->addColumn('movie_title', function($schedule) {
                return $schedule->movie->title ?? '-';
            })
            ->addColumn('formatted_price', function($schedule) {
                return 'Rp ' . number_format($schedule->price, 0, ',', '.');
            })
            ->addColumn('show_times', function($schedule) {
                $times = is_array($schedule->hours) ? $schedule->hours : [];
                return view('components.show-times', ['times' => $times])->render();
            })
            ->addColumn('action', function($schedule) {
                $btnEdit = '<a href="' . route('staff.schedules.edit', $schedule->id) . '" class="btn btn-primary">Edit</a>';
                $btnDelete = '<form action="' . route('staff.schedules.delete', $schedule->id) . '" method="POST" class="d-inline">
                    ' . csrf_field() . method_field('DELETE') . '
                    <button type="submit" class="btn btn-danger ms-2" onclick="return confirm(\'Apakah Anda yakin ingin menghapus jadwal ini?\')">Hapus</button>
                </form>';
                return '<div class="d-flex">' . $btnEdit . $btnDelete . '</div>';
            })
            ->rawColumns(['show_times', 'action'])
            ->make(true);
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
        $request->validate([
            'cinema_id' => 'required',
            'movie_id' => 'required',
            'hours' => 'required|array|min:1',
            'hours.*' => 'required|date_format:H:i',
            'price' => 'required|numeric',
        ], [
            'cinema_id.required' => 'Bioskop harus dipilih',
            'movie_id.required' => 'Film harus dipilih',
            'hours.required' => 'Jam tayang harus diisi',
            'hours.min' => 'Minimal pilih 1 jam tayang',
            'hours.*.required' => 'Jam tayang Diisi minimal 1 data',
            'hours.*.date_format' => 'Jam Tayang Diisi dengan Jam:Menit',
            'price.required' => 'Harga harus diisi',
            'price.numeric' => 'Harga harus diisi dengan angka',
        ]);

        // cek apakah data bioskop dan film yang dipilih sudah ada, kalo ada ambil jamnya
        $schedule = Schedule::where('cinema_id', $request->cinema_id)
            ->where('movie_id', $request->movie_id)
            ->first();

        // Jika ada data yang sudah ada, gunakan hours-nya, jika tidak gunakan array kosong
        $hoursBefore = $schedule ? $schedule->hours : [];

        // Pastikan $request->hours adalah array
        $newHours = is_array($request->hours) ? $request->hours : [];

        // Gabungkan hours sebelumnya dengan hours yang baru ditambahkan
        $mergeHours = array_merge((array)$hoursBefore, $newHours);
        // jika ada jam duplikat, ambil salah satu
        $newHours = array_unique($mergeHours);

        // updateOrCreate([1], [2]) : mengecek berdasarka array 1, jika ada maka update array 2, jika tidak ada tambhahkan data dari array 1 dan 2
        $createData = Schedule::updateOrCreate([
            'cinema_id' => $request->cinema_id,
            'movie_id' => $request->movie_id,
        ], [
            // jam penggabungan sebelum dan bari dari proses diatas
            'hours' => $newHours,
            'price' => $request->price,
        ]);
        if ($createData) {
            return redirect()->route('staff.schedules.index')->with('success', 'Berhasil menambahkan data');
        } else {
            return redirect()->back()->with('error', 'Gagal! coba lagi');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $schedule, $id)
    {
        $schedule = Schedule::where('id', $id)->with(['cinema', 'movie'])->first();
        return view('staff.schedule.edit', compact('schedule'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedule, $id)
    {
        $request->validate([
            'price' => 'required|numeric',
            'hours.*' => 'required|date_format:H:i',
        ], [
            'price.required' => 'Harga harus diisi',
            'price.numeric' => 'Harga harus diisi dengan angka',
            'hours.*.required' => 'Jam tayang Diisi minimal 1 data',
            'hours.*.date_format' => 'Jam Tayang Diisi dengan Jam:Menit',
        ]);

        $updateData = Schedule::where('id', $id)->update([
            'price' => $request->price,
            'hours' => $request->hours,
        ]);
        if ($updateData) {
            return redirect()->route('staff.schedules.index')->with('success', 'Berhasil mengubah data!');
        } else {
            return redirect()->back()->with('error', 'Gagal! coba lagi!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule, $id)
    {
        Schedule::where('id', $id)->delete();
        return redirect()->route('staff.schedules.index')->with('success', 'Berhasil menghapus data!');
    }

    public function trash()
    {
        // onlyTrashed() -> filter data yang sudah dihapus, delete_at bukan null
        $scheduleTrash = Schedule::with(['cinema', 'movie'])->onlyTrashed()->get();
        return view('staff.schedule.trash', compact('scheduleTrash'));
    }

    public function restore($id)
    {
        $schedule = Schedule::onlyTrashed()->find($id);
        // restore() -> mengembalikan data yang sudah dihapus
        $schedule->restore();
        return redirect()->route('staff.schedules.index')->with('success', 'Berhasil mengembalikan data!');
    }

    public function deletePermanent($id)
    {
        $schedule = Schedule::onlyTrashed()->find($id);
        // forceDelete() = menghapus data secara permanen, data hilang bahkan dari db nya
        $schedule->forceDelete();
        return redirect()->back()->with('success', 'Berhasil menghapus data!');
    }

    public function export()
    {
        return Excel::download(new ScheduleExport, 'data-jadwal-tayang.xlsx');
    }
}
