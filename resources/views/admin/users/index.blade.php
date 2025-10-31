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
        <table class="table table-bordered" id="usersTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data akan diisi oleh DataTables -->
            </tbody>
        </table>
    </div>
@endsection

@push('script')
    <script>
        $(function() {
            $('#usersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.users.datatables') }}",
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
                        data: 'email',
                        name: 'email',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'role',
                        name: 'role',
                        orderable: false,
                        searchable: false,
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
