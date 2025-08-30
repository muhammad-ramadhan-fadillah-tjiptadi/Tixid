{{-- Import Templates --}}
@extends('templates.app')

{{-- Ngisi Yield Content --}}
@section('content')
    <div class="dropdown">
        <button class="btn btn-light w-100 text-start dropdown-toggle" type="button" id="dropdownMenuButton"
            data-mdb-dropdown-init data-mdb-ripple-init aria-expanded="false">
            <i class="fa-solid fa-location-dot"></i> Bogor
        </button>
        <ul class="dropdown-menu w-100" aria-labelledby="dropdownMenuButton">
            <li><a class="dropdown-item" href="#">Jakarta Timur</a></li>
            <li><a class="dropdown-item" href="#">Jakarta Barat</a></li>
            <li><a class="dropdown-item" href="#">Jakarta </a></li>
        </ul>
    </div>
    <!-- Carousel wrapper -->
    <div id="carouselBasicExample" class="carousel slide carousel-fade" data-mdb-ride="carousel" data-mdb-carousel-init>
        <!-- Indicators -->
        <div class="carousel-indicators">
            <button type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide-to="0" class="active"
                aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide-to="1"
                aria-label="Slide 2"></button>
            <button type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide-to="2"
                aria-label="Slide 3"></button>
        </div>

        <!-- Inner -->
        <div class="carousel-inner">
            <!-- Single item -->
            <div class="carousel-item active">
                <img style="height: 400px;" src="https://i.pinimg.com/736x/e6/cb/f8/e6cbf8125502392074a479032007fbd7.jpg"
                    class="d-block w-100" alt="Sunset Over the City" />
                <div class="carousel-caption d-none d-md-block">
                    <h5>First slide label</h5>
                    <p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
                </div>
            </div>

            <!-- Single item -->
            <div class="carousel-item">
                <img style="height: 400px" src="https://i.pinimg.com/1200x/11/23/41/1123412b326dbd5de2767fd3a650c95e.jpg"
                    class="d-block w-100" alt="Canyon at Nigh" />
                <div class="carousel-caption d-none d-md-block">
                    <h5>Second slide label</h5>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                </div>
            </div>

            <!-- Single item -->
            <div class="carousel-item">
                <img style="height: 400px;" src="https://i.pinimg.com/1200x/a2/65/08/a26508b9051df79a1c61261c9b615160.jpg"
                    class="d-block w-100" alt="Cliff Above a Stormy Sea" />
                <div class="carousel-caption d-none d-md-block">
                    <h5>Third slide label</h5>
                    <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur.</p>
                </div>
            </div>
        </div>
        <!-- Inner -->

        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
    <!-- Carousel wrapper -->
    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center">
            {{-- Konten Kiri --}}
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-clapperboard"></i>
                <h5 class="ms-2 mt-2">Sedang Tayang</h5>
            </div>
            {{-- Konten Kanan --}}
            <div>
                <button class="btn btn-warning rounded-pill">Semua</button>
            </div>
        </div>
    </div>
    <div class="container d-flex gap-2">
        {{-- Gap - 2 Jarak Antar Komponen --}}
        <button class="btn btn-outline-primary rounded-pill">Semua Film</button>
        <button class="btn btn-outline-secondary rounded-pill">XXI</button>
        <button class="btn btn-outline-secondary rounded-pill">Cinepolis</button>
        <button class="btn btn-outline-secondary rounded-pill">Imax</button>
    </div>
    <div class="container d-flex gap-5 mt-4 justify-content-center">
        <div class="card" style="width: 18rem;">
            <img src="https://i.pinimg.com/736x/4e/ac/c9/4eacc9f48bfb15b1ef9685ce690c1f6a.jpg" class="card-img-top"
                alt="Sunset Over the Sea" style="height: 350px; object-fit: cover;" />
            {{-- Object fit: cover -> gambar ukurannya sesuai dengan height dan width --}}
            <div class="card-body bg-warning" style="padding: 0 !important; text-align: center;">
                {{-- Karna default card text ad paddingnya, biar paddingnya yang dibaca dari style jadi dikasi !important (memprioritaskan style) --}}
                <p class="card-text" style="padding: 0 !important; text-align: center; font-weight: bold;"><a href="{{route('schedules.detail')}}">BELI TIKET</a></p>
            </div>
        </div>
        <div class="card" style="width: 18rem;">
            <img src="https://i.pinimg.com/736x/d5/2b/65/d52b658435f26ec93fb452574ba21f23.jpg" class="card-img-top"
                alt="Sunset Over the Sea" style="height: 350px; object-fit: cover;" />
            {{-- Object fit: cover -> gambar ukurannya sesuai dengan height dan width --}}
            <div class="card-body bg-warning" style="padding: 0 !important; text-align: center;">
                {{-- Karna default card text ad paddingnya, biar paddingnya yang dibaca dari style jadi dikasi !important (memprioritaskan style) --}}
                <p class="card-text" style="padding: 0 !important; text-align: center; font-weight: bold;"><a href="{{route('schedules.detail')}}">BELI TIKET</a></p>
            </div>
        </div>
        <div class="card" style="width: 18rem;">
            <img src="https://i.pinimg.com/736x/cc/00/9a/cc009a1deba43f58e2e98a73db4f8c0c.jpg" class="card-img-top"
                alt="Sunset Over the Sea" style="height: 350px; object-fit: cover;" />
            {{-- Object fit: cover -> gambar ukurannya sesuai dengan height dan width --}}
            <div class="card-body bg-warning" style="padding: 0 !important; text-align: center;">
                {{-- Karna default card text ad paddingnya, biar paddingnya yang dibaca dari style jadi dikasi !important (memprioritaskan style) --}}
                <p class="card-text" style="padding: 0 !important; text-align: center; font-weight: bold;"><a href="{{route('schedules.detail')}}">BELI TIKET</a></p>
            </div>
        </div>
    </div>
    <footer class="bg-body-tertiary text-center text-lg-start mt-5">
        <!-- Copyright -->
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
            © 2020 Copyright:
            <a class="text-body" href="https://mdbootstrap.com/">TIX.ID</a>
        </div>
        <!-- Copyright -->
    </footer>
@endsection
