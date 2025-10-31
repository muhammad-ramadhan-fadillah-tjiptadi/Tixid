@extends('templates.app')

@section('content')
    <div class="container my-5">
        @if (Session::get('success'))
            <div class="alert alert-success">
                {{ Session::get('success') }}
            </div>
        @endif
        @if (Session::get('error'))
            <div class="alert alert-danger">{{ Session::get('error') }}</div>
        @endif
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Data Film</h3>
            <div>
                <a href="{{ route('admin.movies.export') }}" class="btn btn-success me-2">
                    <i class="fas fa-file-excel me-1"></i> Export Excel
                </a>
                <a href="{{ route('admin.movies.trash') }}" class="btn btn-secondary me-2">
                    <i class="fas fa-trash me-1"></i> Data Sampah
                </a>
                <a href="{{ route('admin.movies.create') }}" class="btn btn-primary me-2">
                    <i class="fas fa-plus me-1"></i> Tambah Data
                </a>
            </div>
        </div>
        {{-- <h5 class="mb-3">Data Film</h5> --}}
        <table class="table table-bordered" id="moviesTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Poster</th>
                    <th>Judul Film</th>
                    <th>Status Aktif</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data will be loaded by DataTables -->
            </tbody>
            {{-- @foreach ($movies as $key => $movie)
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
            @endforeach --}}
        </table>
    </div>

    <!-- Modal Detail -->
    <div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetailLabel">Detail Film</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalDetailBody">
                    <!-- Content will be loaded by JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- mengisi stack --}}
@push('script')
    <script>
        $(function() {
            $('#moviesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.movies.datatables') }}",
                responsive: true,
                autoWidth: false,
                // urutan column (td), pastikan urutan sesuai th
                // data: 'nama' -> nama diambil dari rowColumns jika addColumns, atau field dari model fillable
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'poster_img',
                        name: 'poster_img',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'title',
                        name: 'title',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'actived_badge',
                        name: 'actived_badge',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });

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
