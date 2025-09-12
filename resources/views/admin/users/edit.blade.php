@extends('templates.app')

@section('content')
    <div class="w-75 d-block mx-auto my-5 p-4">
        <h5 class="text-center mb-3">Edit Data Staff</h5>
        <form method="POST" action="{{ route('admin.users.update', ['id' => $user['id']]) }}">
            @csrf
            {{-- menimpa method "POST" html menjadi put--}}
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Nama pengguna</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ $user['name'] }}">
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    value="{{ $user['email'] }}">
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                    value="{{ $user['password'] }}">
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Kirim</button>
        </form>
    </div>
@endsection
