<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="{{ asset('template2/img/logo/logo.png') }}" rel="icon">
    <title>Net Digital Group</title>
    <link href="{{ asset('template2/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('template2/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('template2/css/ruang-admin.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('template2/css/ruang-admin.min.css') }}">
    <style>
        body {
            background-color: rgb(255, 255, 255) !important;
        }
    </style>
</head>

<body id="page-top">
    <div id="">
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-navbar topbar mb-4 static-top">
                    <button id="sidebarToggleTop" class="btn btn-default rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <ul class="navbar-nav ml-auto">

                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <span class="badge badge-danger badge-counter">{{ $pemberitahuan->count() }}</span>
                            </a>
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">Pemberitahuan</h6>

                                @foreach ($pemberitahuan as $pemberitahuanItem)
                                    <a class="dropdown-item d-flex align-items-center" href="#">
                                        <div class="mr-3">
                                            <div
                                                class="icon-circle
                                                @if ($pemberitahuanItem->tipe == 'info') bg-primary
                                                @elseif($pemberitahuanItem->tipe == 'keuangan') bg-success
                                                @elseif($pemberitahuanItem->tipe == 'peringatan') bg-warning
                                                @else bg-secondary @endif">
                                                <i
                                                    class="fas
                                                    @if ($pemberitahuanItem->tipe == 'info') fa-file-alt
                                                    @elseif($pemberitahuanItem->tipe == 'keuangan') fa-donate
                                                    @elseif($pemberitahuanItem->tipe == 'peringatan') fa-exclamation-triangle
                                                    @else fa-bell @endif text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="small text-gray-500">
                                                {{ $pemberitahuanItem->created_at->format('d M Y') }}</div>
                                            <span class="font-weight-bold">{{ $pemberitahuanItem->nama }}</span>
                                            <div class="text-truncate">{{ $pemberitahuanItem->pesan }}</div>
                                        </div>
                                    </a>
                                @endforeach

                                <a class="dropdown-item text-center small text-gray-500" href="/pemberitahuan">Lihat
                                    Semua
                                    Pemberitahuan</a>
                            </div>
                        </li>

                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-envelope fa-fw"></i>
                                <span class="badge badge-warning badge-counter">
                                    {{ isset($pesan) ? $pesan->count() : 0 }}
                                </span>
                            </a>
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="messagesDropdown">
                                <h6 class="dropdown-header">Pesan</h6>

                                @if (isset($pesan) && $pesan->count() > 0)
                                    @foreach ($pesan as $pesanItem)
                                        <a class="dropdown-item d-flex align-items-center" href="#">
                                            <div class="dropdown-list-image mr-3">
                                                <img class="rounded-circle" src="{{ asset('template2/img/man.png') }}"
                                                    style="max-width: 60px" alt="">
                                                <div class="status-indicator bg-success"></div>
                                            </div>
                                            <div>
                                                <div class="small text-gray-500">
                                                    {{ $pesanItem->created_at->format('d M Y') }}
                                                </div>
                                                <span class="font-weight-bold">{{ $pesanItem->admin }}</span>
                                                <div class="text-truncate">{{ $pesanItem->pesan }}</div>
                                            </div>
                                        </a>
                                    @endforeach
                                @else
                                    <p class="dropdown-item text-center small text-gray-500">Tidak ada pesan</p>
                                @endif

                                <a class="dropdown-item text-center small text-gray-500" href="/pesan">Lihat
                                    Semua</a>
                            </div>
                        </li>

                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-tasks fa-fw"></i>
                                <span class="badge badge-success badge-counter">{{ $pemberitahuan->count() }}</span>
                            </a>
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="messagesDropdown">
                                <h6 class="dropdown-header">Pekerjaan Team</h6>

                                @foreach ($pemberitahuan as $pekerjaan)
                                    <a class="dropdown-item align-items-center" href="#">
                                        <div class="mb-3">
                                            <div class="small text-gray-500">
                                                {{ $pekerjaan->judul }}
                                                <div class="small float-right"><b>{{ $pekerjaan->progress }}%</b>
                                                </div>
                                            </div>
                                            <div class="progress" style="height: 12px;">
                                                <div class="progress-bar
                                                    @if ($pekerjaan->progress < 30) bg-danger
                                                    @elseif($pekerjaan->progress < 70) bg-warning
                                                    @else bg-success @endif"
                                                    role="progressbar" style="width: {{ $pekerjaan->progress }}%"
                                                    aria-valuenow="{{ $pekerjaan->progress }}" aria-valuemin="0"
                                                    aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                                <a class="dropdown-item text-center small text-gray-500" href="#">Lihat
                                    Semua</a>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                <span style="font-weight: bold; color: white; font-size: 14px; letter-spacing: 1px;"
                                    class="mr-2">
                                    Rp. {{ number_format(Auth::user()->saldo, 0, ',', '.') }}
                                </span>

                                {{-- Cek apakah pengguna memiliki foto di database --}}
                                <img src="{{ asset(Auth::check() && Auth::user()->foto ? Auth::user()->foto : 'asset/img/user/user.png') }}"
                                    alt="Foto Pengguna"
                                    style="max-width: 50px; max-height: 50px; border-radius: 10%;">

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
                                <a class="dropdown-item" href="{{ route('profile') }}">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="{{ route('activity.log') }}">
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
                @yield('konten')
                <!-- Bottom Navbar -->
                <nav class="navbar navbar-dark bg-primary navbar-expand d-md-none d-lg-none d-xl-none fixed-bottom">
                    <ul class="navbar-nav nav-justified w-100">
                        <li class="nav-item">
                            <a href="/masuk/teknisi" class="nav-link text-white">Home</a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="nav-link text-white">Cari</a>
                        </li>
                        <li class="nav-item">
                            <a href="/transfer" class="nav-link text-white">Transfer</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-white">Notif</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('profile') }}" class="nav-link text-white">Profile</a>
                        </li>
                    </ul>
                </nav>

            </div>
        </div>

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
        <!-- Tailwind CSS CDN -->

    </div>
</body>

</html>
