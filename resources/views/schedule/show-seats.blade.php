@extends('templates.app')
@section('content')
    <div class="containter card my-5 p-4" style="margin-bottom: 10% !important">
        <div class="card-body">
            <b>{{ $schedule['cinema']['name'] }}</b>
            {{-- mengambil tanggal hari ini : now(). format('d F, Y') F nama bulan --}}
            <br>
            <b>{{ now()->format('d F, Y') }} - {{ $hour }}</b>
            <br>
            <div class="alert alert-secondary">
                <i class="fa-solid fa-info text-danger me-3"></i>Anak Usia 2 Tahun Keatas Wajib Membeli Tiket.
            </div>
            <div class="w-50 d-block mx-auto my-3">
                <div class="row">
                    <div class="col-4 d-flex">
                        <div style="background: #112646; width: 20px; height: 20px"></div>
                        <span class="ms-2">Kursi Tersedia</span>
                    </div>
                    <div class="col-4 d-flex">
                        <div style="background: blue; width: 20px; height: 20px"></div>
                        <span class="ms-2">Kursi Dipilih</span>
                    </div>
                    <div class="col-4 d-flex">
                        <div style="background: #eaeaea; width: 20px; height: 20px"></div>
                        <span class="ms-2">Kursi Terjual</span>
                    </div>
                </div>
            </div>
            @php
            // range() : membuat array dengan rentang tertentu : range()
                $rows = range('A', 'H');
                $cols = range(1, 18);
            @endphp
            {{-- looping A-H kebawah --}}
            @foreach ($rows as $row)
                <div class="d-flex justify-content-center">
                    @foreach ($cols as $col)
                        {{-- bikin style kotak no kursi --}}
                        {{-- jika kursi no 7 kasih kotak kosong untuk jalan --}}
                        @if ($col == 7)
                            <div style="width: 50px"></div>
                        @endif
                        {{-- bikin style kotak no kursi --}}
                        <div style="background: #112646; color: white; width: 40px; height: 38px; margin: 5px; border-radius: 5px; text-align: center; padding-top: 3px; cursor: pointer;" onclick="selectSeat('{{ $schedule->price }}', '{{ $row }}', '{{ $col }}', this)">
                            <small><b>{{ $row }}-{{ $col }}</b></small>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
    <div class="fixed-bottom">
        <div class="w-100 bg-light text-center px-3" style="border: 1px solid black">
            <b>LAYAR BIOSKOP</b>
        </div>
        <div class="row bg-light">
            <div class="col-6 text-center p-3" style="border: 1px solid black">
                <b>Total Harga</b>
                <br>
                <b id="totalPrice">Rp. -</b>
            </div>
            <div class="col-6 text-center p-3" style="border: 1px solid black">
                <b>Kursi Dipilih</b>
                <br>
                <b id="selectedSeat">-</b>
            </div>
        </div>
        <div class="w-100 bg-light text-center py-3" style="font-weight: bold">RINGKASAN ORDER</div>
    </div>
@endsection

@push('script')
    <script>
        let seats = []; // menyimpan data kursi yang sudah dipilih, bisa lebih dari 1 menggunakan array agar banyak
        function selectSeat(price, row, col, element) {
            // buat format A-1
            let seat = row + "-" + col;
            // cek apakah kursi tersebut ada di array seats atau tidak
            // indexOf : cek item array dan ambil indexnya
            let indexSeat = seats.indexOf(seat);
            // jika ada dapat indexnya jika tidak ada -1
            if (indexSeat == -1) {
                // kalau item gaada didalem array, tambahkan item tsb ke array menggunakan push
                seats.push(seat);
                // kasi warna biru terang
                element.style.background = 'blue';
            } else {
                // jika ada, maka klik kali ini untuk menghapus kursi (batal pilih) menggunakan splice
                seats.splice(indexSeat, 1); // hapus data index ke (yang ketemu)
                // kembalikan warna ke biru tua
                element.style.background = '#112646';
            }

            let totalPrice = price * seats.length; // length : kaya count di php, itung isi array
            let totalPriceElement = document.querySelector("#totalPrice");
            totalPriceElement.innerText = "Rp. " + totalPrice;

            let selectedSeatElement = document.querySelector("#selectedSeat");
            // mengubah array menjadi string dipisahkan dengan koma : join()
            selectedSeatElement.innerText = seats.join(', ');
        }
    </script>
@endpush
