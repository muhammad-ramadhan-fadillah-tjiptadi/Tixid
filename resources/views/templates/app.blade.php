<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TIXID</title>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.1.0/mdb.min.css" rel="stylesheet" />
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <!-- Container wrapper -->
        <div class="container" style="height: 80px">
            <!-- Navbar brand -->
            <a class="navbar-brand me-2" href="{{ Route('home') }}">
                <img src="https://asset.tix.id/wp-content/uploads/2021/10/TIXID_logo_blue-300x82.png" height="16"
                    alt="TIXID Logo" loading="lazy" style="margin-top: -1px;" />
            </a>

            <!-- Toggle button -->
            <button data-mdb-collapse-init class="navbar-toggler" type="button" data-mdb-target="#navbarButtonsExample"
                aria-controls="navbarButtonsExample" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Collapsible wrapper -->
            <div class="collapse navbar-collapse" id="navbarButtonsExample">
                <!-- Left links -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    {{-- Jika sudah login (check) dan rolenya admin (user()->role) --}}
                    @if (Auth::check() && Auth::user()->role == 'admin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a data-mdb-dropdown-init class="nav-link dropdown-toggle" href="#"
                                id="navbarDropdownMenuLink" role="button" aria-expanded="false">
                                Data Master
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.cinemas.index') }}">Data Bioskop</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.movies.index') }}">Data Film</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.users.index') }}">Data Petugas</a>
                                </li>
                            </ul>
                        </li>
                    @elseif (Auth::check() && Auth::user()->role == 'staff')
                        <li class="nav-item">
                            <a class="nav-link" href="#">Jadwal Tiket</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('staff.promos.index')}}">Promo</a>
                        </li>
                    @else
                        {{-- Jika bukan admin/belum login,munculin ini --}}
                        <li class="nav-item">
                            <a class="nav-link" href="#">Beranda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Bioskop</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Tiket</a>
                        </li>
                    @endif
                </ul>
                {{-- <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cinema') }}">Bioskop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('ticket') }}">Tiket</a>
                    </li>
                </ul> --}}
                <!-- Left links -->

                <div class="d-flex align-items-center">
                    {{-- Auth::check() : Mengecheck udah login/belum --}}
                    @if (Auth::check())
                        <a href="{{ route('logout') }}" class="btn btn-danger">logout</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-link text-warning px-3 me-2">
                            Login
                        </a>
                        <a href="{{ route('signup') }}" class="btn btn-warning me-3">
                            Sign up for free
                        </a>
                    @endif
                </div>
            </div>
            <!-- Collapsible wrapper -->
        </div>
        <!-- Container wrapper -->
    </nav>
    <!-- Navbar -->

    {{-- wadah content dinamis --}}
    @yield('content')

    {{-- CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
        integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
        integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous">
    </script>
    <!-- MDB -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.1.0/mdb.umd.min.js"></script>
    @stack('script')
</body>

</html>
