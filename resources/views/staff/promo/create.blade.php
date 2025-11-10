@extends('templates.app')

@section('content')
    <div class="w-75 d-block mx-auto my-5 p-4">
        <h5 class="text-center mb-3">Tambah Data Promo</h5>
        <form method="POST" action="{{ route('staff.promos.store') }}">
            @csrf
            <div class="mb-3">
                <label for="code" class="form-label">Kode</label>
                <input type="text" name="promo_code" value="{{ old('promo_code') }}" class="form-control @error('promo_code') is-invalid @enderror">
                @error('promo_code')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Tipe Promo</label>
                <select name="type" class="form-select @error('type') is-invalid @enderror">
                    <option value="" selected disabled>Pilih Tipe Promo</option>
                    <option value="percent" {{ old('type') == 'percent' ? 'selected' : '' }}>Persentase (%)</option>
                    <option value="rupiah" {{ old('type') == 'rupiah' ? 'selected' : '' }}>Rupiah (Rp)</option>
                </select>
                @error('type')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="discount_amount" class="form-label">Total Potongan</label>
                <input type="number" name="discount" value="{{ old('discount') }}" class="form-control @error('discount') is-invalid @enderror">
                @error('discount')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Kirim</button>
            <a href="{{ route('staff.promos.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection
