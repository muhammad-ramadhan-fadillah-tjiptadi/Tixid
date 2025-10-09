@extends('templates.app')

@section('content')
    <div class="container my-5">
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Data Bioskop</h3>
            <div>
                <a href="{{ route('staff.promos.export') }}" class="btn btn-success me-2">
                    <i class="fas fa-file-excel me-1"></i> Export Excel
                </a>
                <a href="{{ route('staff.promos.trash') }}" class="btn btn-secondary me-2">
                    <i class="fas fa-trash me-1"></i> Data Sampah
                </a>
                <a href="{{ route('staff.promos.create') }}" class="btn btn-primary me-2">
                    <i class="fas fa-plus me-1"></i> Tambah Data
                </a>
            </div>
        </div>
        {{-- <h5 class="mt-3">Data Promo</h5> --}}
        <table class="table table-bordered">
            <tr>
                <th>No</th>
                <th>Kode Promo</th>
                <th>Total Potongan</th>
                <th>Aksi</th>
            </tr>
            @forelse ($promos as $index => $promo)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $promo->promo_code }}</td>
                    <td>
                        @if ($promo->type === 'percent')
                            {{ number_format($promo->discount, 0, ',', '.') }}%
                        @else
                            Rp {{ number_format($promo->discount, 0, ',', '.') }}
                        @endif
                    </td>
                    <td class="d-flex">
                        <a href="{{ route('staff.promos.edit', $promo->id) }}" class="btn btn-primary me-2">Edit</a>
                        <form action="{{ route('staff.promos.delete', $promo->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
            @endforelse
        </table>
    </div>
@endsection
