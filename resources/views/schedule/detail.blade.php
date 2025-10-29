@extends('templates.app')

@section('content')
    <div class="container pt-5">
        <div class="w-75 d-block m-auto">
            <div class="d-flex">
                <div style="width: 150px; height: 200px;">
                    <img src="{{ asset('storage/' . $movie['poster']) }}" alt="" class="w-100">
                </div>
                <div class="ms-5 mt-4">
                    <h5>{{ $movie['title'] }}</h5>
                    <table>
                        <tr>
                            <td><b class="text-secondary">Genre</b></td>
                            <td class="px-3"></td>
                            <td>{{ $movie['genre'] }}</td>
                        </tr>
                        <tr>
                            <td><b class="text-secondary">Durasi</b></td>
                            <td class="px-3"></td>
                            <td>{{ $movie['duration'] }}</td>
                        </tr>
                        <tr>
                            <td><b class="text-secondary">Sutradara</b></td>
                            <td class="px-3"></td>
                            <td>{{ $movie['director'] }}</td>
                        </tr>
                        <tr>
                            <td><b class="text-secondary">Rating Usia</b></td>
                            <td class="px-3"></td>
                            <td><span class="badge badge-danger">{{ $movie['age_rating'] }}</span></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="w-100 row mt-5">
                <div class="col-6 pe-5">
                    <div class="d-flex flex-column justify-content-end align-items-end">
                        <div class="d-flex align-items-center">
                            <h3 class="text-warning me-2">9.0</h3>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <small>336 Vote</small>
                    </div>
                </div>
                <div class="col-6 ps-5" style="border-left: 2px solid #c7c7c7">
                    <div class="d-flex align-items-center">
                        <div class="fas fa-heart text-danger me-2">
                        </div>
                        <b>Masukan Watchlist</b>
                    </div>
                    <small>1.000 Orang</small>
                </div>
                <div class="d-flex w-100 bg-light mt-3 gap-3">
                    <div class="dropdown">
                        <button class="btn btn-light w-100 text-start dropdown-toggle" type="button"
                            id="dropdownMenuButton" data-mdb-dropdown-init data-mdb-ripple-init aria-expanded="false">
                            <i class="fa-solid fa-location-dot"></i> Bioskop
                        </button>
                        <ul class="dropdown-menu w-100" aria-labelledby="dropdownMenuButton">
                            @foreach ($movie['schedules'] as $schedule)
                                <li><a href="" class="dropdown-item">{{ $schedule['cinema']['name'] }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    @php
                        // request()->get('name query') : memanggil query params (?) di url
                        // jika ? nilainya ASC ubah jd DESC
                        if (Request()->get('sort_price') == 'ASC') {
                            $sortPrice = 'DESC';
                        } elseif (Request()->get('sort_price') == 'DESC') {
                            // jiak query params sort_price DESC, uabh jadi ASC
                            $sortPrice = 'ASC';
                        } else {
                            $sortPrice = 'ASC';
                        }

                        if (Request()->get('sort_alfabet') == 'ASC') {
                            $sortAlfabet = 'DESC';
                        } elseif (Request()->get('sort_alfabet') == 'DESC') {
                            // jiak query params sort_alfabet DESC, uabh jadi ASC
                            $sortAlfabet = 'ASC';
                        } else {
                            $sortAlfabet = 'ASC';
                        }
                    @endphp
                    <div class="dropdown">
                        <button class="btn btn-light w-100 text-start dropdown-toggle" type="button"
                            id="dropdownMenuButton" data-mdb-dropdown-init data-mdb-ripple-init aria-expanded="false">
                            <i class="fa-solid fa-location-dot"></i> Bogor
                        </button>
                        <ul class="dropdown-menu w-100" aria-labelledby="dropdownMenuButton">
                            <li><a class="dropdown-item" href="?sort_price={{ $sortPrice }}">Harga</a></li>
                            <li><a class="dropdown-item" href="?sort_alfabet={{ $sortAlfabet }}">Alphabet</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="mb-5">
                @foreach ($movie['schedules'] as $schedule)
                    <div class="w-100 my-3">
                        <div class="d-flex justify-content-between">
                            {{-- kanan --}}
                            <div class="">
                                <i class="fa-solid fa-building"></i><b class="ms-2">{{ $schedule['cinema']['name'] }}</b>
                                <br>
                                <small class="ms-3">{{ $schedule['cinema']['location'] }}</small>
                            </div>

                            {{-- kiri --}}
                            <div class="">
                                <b>Rp. {{ number_format($schedule['price'], 0, ',', '.') }}</b>
                            </div>
                        </div>
                        <div class="d-flex gap-3 ps-3 my-2">
                            @foreach ($schedule['hours'] as $hours)
                                <div class="btn btn-outline-secondary">{{ $hours }}</div>
                            @endforeach
                        </div>
                    </div>
                    <hr>
                @endforeach
                <div class="w-100 p-2 bg-light text-center fixed-bottom">
                    <a href=""><i class="fa-solid fa-ticket"></i>Beli Tiket</a>
                </div>
            </div>
        </div>
    @endsection
