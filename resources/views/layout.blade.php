<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('asset/img/logo.png') }}" rel="icon">
    <title>Net Digital Group</title>
    <link href="{{ asset('template2/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('template2/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('template2/css/ruang-admin.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('template2/css/ruang-admin.min.css') }}">

</head>

<body id="page-top">
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/masuk/admin">
                <div class="sidebar-brand-icon">
                    <img src="{{ asset('asset/img/logo.png') }}">
                </div>
                <div class="sidebar-brand-text mx-3">Net Digital Group</div>
            </a>
            <hr class="sidebar-divider my-0">
            <li class="nav-item active">
                <a class="nav-link" href="/masuk/admin">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>





            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrap"
                    aria-expanded="true" aria-controls="collapseBootstrap1">
                    <img src="{{ asset('asset/img/pelanggan.png') }}" alt="Gambar Pelanggan"
                        style="width: 30px; height: auto; margin-left: 10px;" class="mr-2">
                    <span class="font-weight-bold " style="color: black">Pelanggan</span>
                </a>
                <div id="collapseBootstrap" class="collapse" aria-labelledby="headingBootstrap1"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded font-weight-bold" style="color: black">
                        <a class="collapse-item" href="/pelanggan">Pelanggan Aktif</a>
                        <a class="collapse-item" href="/pelanggan/isolir/">Pelanggan Isolir</a>
                        <!-- <a class="collapse-item" href="/pelanggan/unblock/">PELANGGAN Unblock</a>
                        <a class="collapse-item" href="/pelanggan/automatispayment/">Pelanggan Bayar sendiri</a>
                        <a class="collapse-item" href="/pelanggan/block/">PELANGGAN Block</a>  -->
                        <a class="collapse-item" href="/pelanggan/reactivasi/">Pelanggan Reactivasi</a>
                        <a class="collapse-item" href="/pelanggan/psb/">Pelanggan PSB</a>
                        <a class="collapse-item" href="/pelangganof/">Pelanggan OFF</a>
                        <a class="collapse-item" href="/odp/">ODP Pelanggan</a>
                        <a class="collapse-item" href="/data-maps-pelanggan">Maps Pelanggan</a>
                        <a class="collapse-item" href="/data-odp/">Data Tiang</a>
                        <a class="collapse-item" href="/generator">Buat ID Pelanggan</a>
                        <a class="collapse-item" href="/rekap_pemasangan/">Rekap Pemasangan</a>

                    </div>
                </div>
            </li>




            <!--  <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse"
                    data-target="#collapseBootstrap13" aria-expanded="true" aria-controls="collapseBootstrap13">
                    <img src="{{ asset('asset/img/baru.png') }}" alt="Gambar Pelanggan"
                        style="width: 30px; height: auto; margin-left: 10px;" class="mr-2">
                    <span class=" font-weight-bold">Rekap Pemasangan</span>
                </a>
                <div id="collapseBootstrap13" class="collapse" aria-labelledby="headingBootstrap13"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded font-weight-bold" style="color: black">
                        <a class="collapse-item" href="/rekap_pemasangan/">Riwayat Pemasangan</a>
                        <a class="collapse-item" href="/rekap_pemasangan/create">Buat Form Pemasangan</a>
                    </div>
                </div>
            </li> -->


            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse"
                    data-target="#collapseBootstrap1" aria-expanded="true" aria-controls="collapseBootstrap1">
                    <img src="{{ asset('asset/img/perbaikan.png') }}" alt="Gambar Pelanggan"
                        style="width: 30px; height: auto; margin-left: 10px;" class="mr-2">
                    <span class=" font-weight-bold">PSB dan Perbaikan</span>
                </a>
                <div id="collapseBootstrap1" class="collapse" aria-labelledby="headingBootstrap1"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded font-weight-bold" style="color: black">

                        <a class="collapse-item" href="/perbaikan/tiket/">Tiket</a>
                        <a class="collapse-item" href="/perbaikan/">Pemasangan - Perbaikan</a>
                        <a class="collapse-item" href="{{ route('psb.create') }}">Buat PSB dan Perbaikan Costume</a>


                    </div>
                </div>
            </li>



            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse"
                    data-target="#collapseBootstrap22" aria-expanded="true" aria-controls="collapseBootstrap22">
                    <img src="{{ asset('asset/img/icon/absen.png') }}" alt="Gambar Pelanggan"
                        style="width: 35px; height: auto; margin-left: 10px;" class="mr-2">
                    <span class=" font-weight-bold">Absen Kehadiran</span>
                </a>
                <div id="collapseBootstrap22" class="collapse" aria-labelledby="headingBootstrap17"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded  font-weight-bold" style="color: black">
                        <a class="collapse-item" href="/x100c/show/">Data Kehadiran</a>
                        <a class="collapse-item" href="/home/tv">Home TV</a>


                        <!--   <a class="collapse-item" href="/absensi/dashboard">Data Kehadiran</a>
                        <a class="collapse-item" href="/absensi/absen">Coba Absen</a> -->
                    </div>

                </div>
            </li>














            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse"
                    data-target="#collapseBootstrap25" aria-expanded="true" aria-controls="collapseBootstrap25">
                    <img src="{{ asset('asset/img/icon/inventory.png') }}" alt="Gambar Pelanggan"
                        style="width: 40px; height: auto; margin-left: 10px;" class="mr-2">
                    <span class=" font-weight-bold">Inventori</span>
                </a>
                <div id="collapseBootstrap25" class="collapse" aria-labelledby="headingBootstrap17"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded  font-weight-bold" style="color: black">
                        <a class="collapse-item" href="/modem/">Modem</a>
                        <a class="collapse-item" href="/modem_hp/">Lihat Modem diHp</a>
                        <!--  <a class="collapse-item" href="/adapter/">Adaptor</a>
                        <a class="collapse-item" href="">Pathcore</a> -->
                    </div>
                </div>
            </li>


            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse"
                    data-target="#collapseBootstrap20" aria-expanded="true" aria-controls="collapseBootstrap20">
                    <img src="{{ asset('asset/img/folder.png') }}" alt="Gambar Pelanggan"
                        style="width: 30px; height: auto; margin-left: 10px;" class="mr-2">
                    <span class=" font-weight-bold">File</span>
                </a>
                <div id="collapseBootstrap20" class="collapse" aria-labelledby="headingBootstrap17"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded  font-weight-bold" style="color: black">
                        <a class="collapse-item" href="/file/index">File</a>
                    </div>

                </div>
            </li>



        </ul>
        <!-- Sidebar -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- TopBar -->
                <!-- TopBar -->
                <nav class="navbar navbar-expand navbar-light bg-navbar topbar mb-4 static-top">
                    <button id="sidebarToggleTop" class="btn btn-default rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right  shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-1 small"
                                            placeholder="What do you want to look for?" aria-label="Search"
                                            aria-describedby="basic-addon2" style="border-color: #b53f3f;">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <span class="badge badge-danger badge-counter">3+</span>
                            </a>
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">
                                    Pemberitahuan
                                </h6>
                                <!--   <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-file-alt text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">Net Digital Group </div>
                                        <span class="font-weight-bold">Net Digital Group</span>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-success">
                                            <i class="fas fa-donate text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">Net Digital Group</div>
                                        Net Digital Group
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-warning">
                                            <i class="fas fa-exclamation-triangle text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">Net Digital Group</div>
                                        Net Digital Group
                                    </div>
                                </a> -->
                                <a class="dropdown-item text-center small text-gray-500" href="#">NET DIGITAL
                                    GROUP</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-envelope fa-fw"></i>
                                <span class="badge badge-warning badge-counter">2</span>
                            </a>
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="messagesDropdown">
                                <h6 class="dropdown-header">
                                    Pesan
                                </h6>
                                <!--    <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="{{ asset('template2/img/man.png') }}"
                                            style="max-width: 60px" alt="">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div class="font-weight-bold">
                                        <div class="text-truncate">Net Digital Group.</div>
                                        <div class="small text-gray-500">Net Digital Group</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/girl.png" style="max-width: 60px"
                                            alt="">
                                        <div class="status-indicator bg-default"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">Net Digital Group</div>
                                        <div class="small text-gray-500">JNet Digital Group</div>
                                    </div>
                                </a> -->
                                <a class="dropdown-item text-center small text-gray-500" href="#">Lihat Semua
                                </a>
                            </div>
                        </li>
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-tasks fa-fw"></i>
                                <span class="badge badge-success badge-counter">3</span>
                            </a>
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="messagesDropdown">
                                <h6 class="dropdown-header">
                                    Pekerjaan Team
                                </h6>
                                <!--   <a class="dropdown-item align-items-center" href="#">
                                    <div class="mb-3">
                                        <div class="small text-gray-500">Develope Frontend
                                            <div class="small float-right"><b>50%</b></div>
                                        </div>
                                        <div class="progress" style="height: 12px;">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                style="width: 50%" aria-valuenow="50" aria-valuemin="0"
                                                aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </a> -->

                                <a class="dropdown-item text-center small text-gray-500" href="#">Lihat
                                    Semua</a>
                            </div>
                        </li>
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{-- Cek apakah pengguna memiliki foto di database --}}
                                <img src="{{ asset(Auth::check() && Auth::user()->foto ? Auth::user()->foto : 'asset/img/user/user.png') }}"
                                    alt="Foto Pengguna"
                                    style="max-width: 50px; max-height: 50px; border-radius: 50%;">


                                <div class="ml-2 mt-4 d-none d-lg-inline text-white small">
                                    @if (Auth::check())
                                        {{-- Mengecek apakah pengguna sudah login --}}
                                        <span style="font-weight: bold; color: white;">{{ Auth::user()->name }}</span>
                                        {{-- Tampilkan nama pengguna --}}
                                        <ul class="list-group list-group-flush"
                                            style="background-color: transparent;">
                                            @if (Auth::user()->role == 'teknisi')
                                                <li class="list-group-item"
                                                    style="background-color: transparent; border: none; color: white; font-weight: bold;">

                                                </li>
                                            @endif

                                            @if (Auth::user()->role == 'admin')
                                                <li class="list-group-item"
                                                    style="background-color: transparent; border: none; color: white; font-weight: bold;">

                                                </li>
                                            @endif

                                            @if (Auth::user()->role == 'superadmin')
                                                <li class="list-group-item"
                                                    style="background-color: transparent; border: none; color: white; font-weight: bold;">

                                                </li>
                                            @endif
                                        </ul>
                                    @else
                                        <script>
                                            window.location.href = "{{ route('login') }}";
                                        </script>
                                    @endif

                                </div>
                            </a>


                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/logout">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>



                <div class="d-flex flex-column align-items-center justify-content-center">
                    <h6 class="h6 text-center" style="color: black;"></h6>
                    <ol class="breadcrumb d-flex align-items-center">
                        <!-- Jam Berjalan -->
                        <div class="h6 font-weight-bold mr-3" style="color: black;">
                            <span id="liveClock"></span>
                        </div>
                        <div class="h6 font-weight-bold" style="color: black;">
                            {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                        </div>
                    </ol>
                </div>

                <script>
                    function updateClock() {
                        const now = new Date();
                        const hours = String(now.getHours()).padStart(2, '0');
                        const minutes = String(now.getMinutes()).padStart(2, '0');
                        const seconds = String(now.getSeconds()).padStart(2, '0');
                        const formattedTime = `${hours}:${minutes}:${seconds}`;
                        document.getElementById('liveClock').textContent = formattedTime;
                    }

                    // Update jam setiap detik
                    setInterval(updateClock, 1000);
                    updateClock(); // Panggil fungsi segera untuk menampilkan waktu saat ini tanpa menunggu 1 detik
                </script>


                <!-- Topbar -->

                <!-- Container Fluid-->
                @yield('konten')
                <!-- Footer -->
            </div>
        </div>

        <!-- Scroll to top -->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <script src="{{ asset('template2/vendor/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('template2/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('template2/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
        <script src="{{ asset('template2/js/ruang-admin.min.js') }}"></script>
        <script src="{{ asset('template2/vendor/chart.js/Chart.min.js') }}"></script>
        <script src="{{ asset('template2/js/demo/chart-area-demo.js') }}"></script>

        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="{{ asset('js/script.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}">
        </script>

        <!-- jQuery -->


        <!-- Select2 CSS -->


        <!-- Select2 JS -->






    </div>


</body>

</html>
