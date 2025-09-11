@extends('templates.app')

@section('content')
    <div class="d-flex justify-content-end">
        <a href="" class="btn btn-success">Tambah Data</a>
    </div>
    <h5 class="mt-3">Data Bioskop</h5>
    <table class="table table-bordered">
        <tr>
            <th>#</th>
            <th>Nama Bioskop</th>
            <th>Lokasi Bioskop</th>
            <th>Aksi</th>
        </tr>
        {{-- $cinemas : dari compact, karna pake all jadi array dimensi --}}
        @foreach ($cinemas as $index => $item)
            <tr>
                {{-- $index dari nol, biar muncul 1 -> +1 --}}
                <th>{{ $index+1 }}</th>
                {{-- name. location dari fillable model cinemas --}}
                <th>{{ $item['name'] }}</th>
                <th>{{ $item['location'] }}</th>
                <th class="d-flex">
                    <a href="" class="btn btn-secondary">Edit</a>
                    <button class="btn btn-danger">Hapus</button>
                </th>
            </tr>
        @endforeach
    </table>
@endsection
