@extends('templates.app')

@section('content')
    <div class="container mt-5">
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif
        @if (Session::get('error'))
            <div class="alert alert-danger">{{ Session::get('error') }}</div>
        @endif
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Data Bioskop</h3>
            <div>
                <a href="{{ route('admin.cinemas.export') }}" class="btn btn-success me-2">
                    <i class="fas fa-file-excel me-1"></i> Export Excel
                </a>
                <a href="{{ route('admin.cinemas.trash') }}" class="btn btn-secondary me-2">
                    <i class="fas fa-trash me-1"></i> Data Sampah
                </a>
                <a href="{{ route('admin.cinemas.create') }}" class="btn btn-primary me-2">
                    <i class="fas fa-plus me-1"></i> Tambah Data
                </a>
            </div>
        </div>
        {{-- <h5 class="mt-3">Data Bioskop</h5> --}}
        <table class="table table-bordered" id="cinemaTables">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Bioskop</th>
                    <th>Lokasi Bioskop</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
@endsection

@push('script')
    <script>
        $(function() {
            $('#cinemaTables').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.cinemas.datatables') }}",
                responsive: true,
                autoWidth: false,
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'name',
                        name: 'name',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'location',
                        name: 'location',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                    }
                ]
            });
        });
    </script>
@endpush
