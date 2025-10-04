@extends('templates.app')

@section('content')
    <div class="container my-5">
        @if (Session::get('success'))
            <div class="alert alert-success">
                {{ Session::get('success') }}
            </div>
        @endif
        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.movies.export') }}" class="btn btn-secondary me-2">Export (.xlsx)</a>
            <a href="{{ route('admin.movies.create') }}" class="btn btn-success">Tambah Data</a>
        </div>
        <h5 class="mb-3">Data Film</h5>
        <table class="table table-bordered">
            <tr>
                <th>No</th>
                <th>Poster</th>
                <th>Judul Film</th>
                <th>Status Aktif</th>
                <th>Aksi</th>
            </tr>
            @foreach ($movies as $key => $movie)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>
                        <img src="{{ asset('storage/' . $movie->poster) }}" width="120" class="img-fluid">
                    </td>
                    <td>{{ $movie->title }}</td>
                    <td>
                        @if ($movie->activated == 1)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-danger">Non-Aktif</span>
                        @endif
                    </td>
                    <td class="d-flex">
                        <button class="btn btn-secondary me-2" onclick="showModal({{ $movie }})">Detail</button>
                        <a href="{{ route('admin.movies.edit', $movie->id) }}" class="btn btn-primary me-2">Edit</a>
                        <form action="{{ route('admin.movies.delete', $movie->id) }}" method="POST" class="me-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                        <form action="{{ route('admin.movies.patch', $movie->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            @if ($movie->activated == 1)
                                <button type="submit" class="btn btn-warning">Non-Aktifkan</button>
                            @endif
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
        {{-- Modal --}}
        <div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Detail Film</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modalDetailBody">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- mengisi stack --}}
@push('script')
    <script>
        function showModal(item) {
            // console.log(item)
            // pengambilan gambar di public
            let image = "{{ asset('storage/') }}" + "/" + item.poster;
            // membuat konten yang akan ditambahkan
            // backlip (diatas tab) : menulis string lebih dari 1 baris
            let content = `
                <img src="${image}" width="120" class="d-block mx-auto my-3">
                <ul>
                    <li>Judul : ${item.title}</li>
                    <li>Durasi : ${item.duration}</li>
                    <li>Genre : ${item.genre}</li>
                    <li>Sutradara : ${item.director}</li>
                    <li>Usia Minimal : <span class="badge badge-danger">${item.age_rating}</span></li>
                    <li>Sinopsis : ${item.description}</li>
                </ul>
            `;
            let modalDetailBody = document.querySelector("#modalDetailBody");
            // isi html diatas ke id="modalDetailBody"
            modalDetailBody.innerHTML = content;
            let modalDetail = document.querySelector("#modalDetail");
            // munculkan modal bootsrap
            new bootstrap.Modal(modalDetail).show();
        }
    </script>
@endpush
