@extends('templates.app')

@section('content')
    <div class="container my-5">
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.movies.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
        <h3 class="my-3">Data Sampah Film</h3>
        @if (Session::get('success'))
            <div class="alert alert-success">
                {{ Session::get('success') }}
            </div>
        @endif
        <table class="table table-bordered">
            <tr>
                <th>No</th>
                <th>Poster</th>
                <th>Judul Film</th>
                <th>Status Aktif</th>
            </tr>
            @foreach ($movieTrash as $key => $movie)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    {{-- memunculkan detail relasi : $item['namarelasi']['data'] --}}
                    <td>{{ $movie['poster'] ?? '-'}}</td>
                    <td>{{ $movie['title'] ?? '-'}}</td>
                    <td>{{ $movie['activated'] ?? '-'}}</td>
                    <td class="d-flex">
                        <form action="{{ route('admin.movies.restore', $movie->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-success ms-2">Kembalikan</button>
                        </form>
                        <form action="{{ route('admin.movies.delete-permanent', $movie->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger ms-2">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
