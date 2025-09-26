@extends('templates.app')

@section('content')
    <div class="container mt-5">
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
        <div class="d-flex justify-content-end">
            <a href="{{ route('staff.promos.create') }}" class="btn btn-success">Tambah Data</a>
        </div>
        <h5 class="mt-3">Data Promo</h5>
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
                        @if($promo->type === 'percent')
                            {{ number_format($promoh->discount, 0, ',', '.') }}%
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
