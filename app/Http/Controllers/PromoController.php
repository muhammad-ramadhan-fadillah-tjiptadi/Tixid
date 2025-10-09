<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use App\Exports\PromoExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promos = Promo::all();
        return view('staff.promo.index', compact('promos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('staff.promo.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'promo_code' => 'required',
            'type' => 'required|in:rupiah,percent',
            'discount' => $request->type === 'rupiah'
                ? 'required|numeric|min:500'
                : 'required|numeric|min:0|max:100',
        ], [
            'promo_code.required' => 'Kode promo wajib diisi',
            'type.required' => 'Tipe diskon wajib diisi',
            'type.in' => 'Tipe diskon harus berupa "rupiah" atau "percent"',
            'discount.required' => 'Total potongan wajib diisi',
            'discount.numeric' => 'Diskon harus berupa angka',
            'discount.min' => 'Diskon dalam rupiah minimal Rp 500',
            'discount.max' => 'Diskon dalam persen maksimal 100%',
        ]);
        $createData = Promo::create([
            'promo_code' => $request->promo_code,
            'type' => $request->type,
            'discount' => $request->discount,
            'activated' => 1
        ]);
        if ($createData) {
            // redirect untuk mengarahkan ke route, with adalah untuk memberikan pesan
            return redirect()->route('staff.promos.index')->with('success', 'Berhasil menambahkan data!');
        } else {
            return redirect()->back()->with('error', 'Gagal menambahkan data! Silahkan coba lagi!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Promo $promo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $promos = Promo::find($id);
        return view('staff.promo.edit', compact('promos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'promo_code' => 'required',
            'type' => 'required|in:rupiah,percent',
            'discount' => $request->type === 'rupiah'
                ? 'required|numeric|min:500'
                : 'required|numeric|max:100',
        ], [
            'promo_code.required' => 'Kode promo wajib diisi',
            'type.required' => 'Tipe diskon wajib diisi',
            'type.in' => 'Tipe diskon harus berupa "rupiah" atau "percent"',
            'discount.required' => 'Total potongan wajib diisi',
            'discount.numeric' => 'Diskon harus berupa angka',
            'discount.min' => 'Diskon dalam rupiah minimal Rp 500',
            'discount.max' => 'Diskon dalam persen maksimal 100%',
        ]);
        $createData = Promo::where('id', $id)->update([
            'promo_code' => $request->promo_code,
            'type' => $request->type,
            'discount' => $request->discount,
            'activated' => 1
        ]);
        if ($createData) {
            return redirect()->route('staff.promos.index')->with('success', 'Berhasil merubah data!');
        } else {
            return redirect()->back()->with('error', 'Gagal merubah data! Silahkan coba lagi!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $promo = Promo::findOrFail($id);
        $promo->delete();
        return redirect()->route('staff.promos.index')->with('success', 'Data berhasil dihapus!');
    }

    /**
     * Export promos to Excel
     */
    public function export()
    {
        $filename = 'data-promo.xlsx';
        return Excel::download(new PromoExport, $filename);
    }

    public function trash()
    {
        $promoTrash = Promo::onlyTrashed()->get();
        return view('staff.promo.trash', compact('promoTrash'));
    }

    public function restore($id)
    {
        $promo = Promo::onlyTrashed()->findOrFail($id);
        $promo->restore();
        return redirect()->route('staff.promos.index')->with('success', 'Berhasil mengembalikan data promo!');
    }

    public function deletePermanent($id)
    {
        $promo = Promo::onlyTrashed()->findOrFail($id);
        $promo->forceDelete();
        return redirect()->route('staff.promos.trash')->with('success', 'Data promo berhasil dihapus permanen!');
    }
}
