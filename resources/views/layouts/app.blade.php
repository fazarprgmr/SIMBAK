<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', 'Sistem Informasi')</title>
    <meta name="description" content="Sistem Informasi Barang">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" href="{{ asset('template/images/logo-simbak1.png') }}">


    {{-- Vendor CSS --}}
    <link rel="stylesheet" href="{{ asset('template/vendors/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/vendors/themify-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('template/vendors/flag-icon-css/css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/vendors/selectFX/css/cs-skin-elastic.css') }}">
    <link rel="stylesheet" href="{{ asset('template/vendors/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/css/style.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800" rel="stylesheet">
</head>

<body>

    <!-- Sidebar -->
    <aside id="left-panel" class="left-panel">
        <nav class="navbar navbar-expand-sm navbar-default">
            <div class="navbar-header">
                <a class="navbar-brand" href="{{ route('dashboard') }}">
                    <img src="{{ asset('template/images/logo-simbak.jpg') }}" alt="Logo"
                        style="width: 150px; height: auto;">
                </a>

                <a class="navbar-brand hidden" href="{{ route('dashboard') }}">
                    <img src="{{ asset('template/images/logo-simbaknobg.png') }}" alt="Logo">
                </a>

            </div>
            <div id="main-menu" class="main-menu collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="{{ isActive(['dashboard']) }}">
                        <a href="{{ route('dashboard') }}"><i class="menu-icon fa fa-home"></i> Dashboard</a>
                    </li>
                    <li class="{{ isActive(['rka.index', 'rka.create', 'rka.edit']) }}">
                        <a href="{{ route('rka.index') }}"><i class="menu-icon fa fa-dashboard"></i> Data Pemasukan</a>
                    </li>
                    <li class="{{ isActive(['kode-rekenings.*']) }}">
                        <a href="{{ route('kode-rekenings.index') }}"><i class="menu-icon fa fa-book"></i> Kode
                            Rekening</a>
                    </li>
                    <li class="{{ isActive(['satuans.*']) }}">
                        <a href="{{ route('satuans.index') }}"><i class="menu-icon fa fa-cube"></i> Satuan Barang</a>
                    </li>
                    <li class="{{ isActive(['rka.laporan', 'rka.export.*']) }}">
                        <a href="{{ route('rka.laporan') }}"><i class="menu-icon fa fa-bar-chart"></i> Laporan</a>
                    </li>

                </ul>
            </div>
        </nav>
    </aside>
    <!-- /Sidebar -->

    <!-- Main Panel -->
    <div id="right-panel" class="right-panel">

        <!-- Header -->
        <header id="header" class="header">
            <div class="header-menu">
                <div class="col-sm-7">
                    <a id="menuToggle" class="menutoggle pull-left"><i class="fa fa fa-tasks"></i></a>
                    <div class="header-left">
                        <button class="search-trigger"><i class="fa fa-search"></i></button>
                        <div class="form-inline">
                            <form class="search-form">
                                <input class="form-control mr-sm-2" type="text" placeholder="Cari ..."
                                    aria-label="Search">
                                <button class="search-close" type="submit"><i class="fa fa-close"></i></button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-sm-5">
                    <div class="user-area dropdown float-right">
                        <a href="#" class="dropdown-toggle d-flex align-items-center" data-toggle="dropdown">
                            <img class="user-avatar rounded-circle"
                                src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : asset('template/images/img-default.jpg') }}"
                                alt="User Avatar">
                            <span class="ml-2">{{ Auth::user()->name ?? 'User' }}</span>

                        </a>
                        <div class="user-menu dropdown-menu dropdown-menu-right">
                            <!-- Profil -->
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="fa fa-user"></i> Profil
                            </a>

                            <!-- Pengaturan -->
                            <a class="dropdown-item" href="#">
                                <i class="fa fa-cog"></i> Pengaturan
                            </a>

                            <!-- Logout -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fa fa-power-off"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </header>
        <!-- /Header -->

        <!-- Content -->
        <div class="content mt-3">
            @yield('content')
        </div>
        <!-- /Content -->

    </div>
    <!-- /Main Panel -->

    {{-- Vendor JS --}}
    <script src="{{ asset('template/vendors/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('template/vendors/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('template/vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('template/assets/js/main.js') }}"></script>
    <script src="{{ asset('template/vendors/chart.js/dist/Chart.bundle.min.js') }}"></script>
    <script src="{{ asset('template/assets/js/init-scripts/chart-js/chartjs-init.js') }}"></script>

    @stack('scripts')

</body>

</html>
