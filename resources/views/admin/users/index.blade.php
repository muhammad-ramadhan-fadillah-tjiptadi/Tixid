@extends('templates.app')

@section('content')
    <div class="container mt-5">
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Data Pengguna (Admin & Staff)</h3>
            <div>
                <a href="{{ route('admin.users.export') }}" class="btn btn-success me-2">
                    <i class="fas fa-file-excel me-1"></i> Export Excel
                </a>
                <a href="{{ route('admin.users.trash') }}" class="btn btn-secondary me-2">
                    <i class="fas fa-trash me-1"></i> Data Sampah
                </a>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary me-2">
                    <i class="fas fa-plus me-1"></i> Tambah Data
                </a>
            </div>
        </div>
        {{-- <h5 class="mt-3">Data Pengguna (Admin & Staff)</h5> --}}
        <table class="table table-bordered">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
            </tr>
            {{-- $users : dari compact, karna pake all jadi array dimensi --}}
            @foreach ($users as $index => $item)
                <tr>
                    {{-- $index dari nol, biar muncul 1 -> +1 --}}
                    <th>{{ $index + 1 }}</th>
                    {{-- name. location dari fillable model cinemas --}}
                    <th>{{ $item['name'] }}</th>
                    <th>{{ $item['email'] }}</th>
                    <th>
                        @if ($item['role'] == 'admin')
                            <span class="badge badge-primary">Admin</span>
                        @elseif($item['role'] == 'staff')
                            <span class="badge badge-success">Staff</span>
                        @elseif($item['role'] == 'user')
                            <span class="badge badge-warning">User</span>
                        @endif
                    </th>
                    <th class="d-flex">
                        {{-- ['id' => $item['id']] : mengirimkan $item['id'] ke route {'id'} --}}
                        <a href="{{ route('admin.users.edit', ['id' => $item['id']]) }}" class="btn btn-secondary">Edit</a>
                        <form action="{{ route('admin.users.delete', ['id' => $item['id']]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger ms-3">Hapus</button>
                        </form>
                    </th>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
