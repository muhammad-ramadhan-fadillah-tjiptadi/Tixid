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
        <table class="table table-bordered" id="promoTables">
            <tr>
                <th>No</th>
                <th>Kode Promo</th>
                <th>Total Potongan</th>
                <th>Aksi</th>
            </tr>
        </table>
    </div>
@endsection

@push('script')
    <script>
        $(function() {
            $('#promoTables').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('staff.promos.datatables') }}",
                responsive: true,
                autoWidth: false,
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'promo_code',
                        name: 'promo_code',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'discount',
                        name: 'discount',
                        orderable: false,
                        searchable: false
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