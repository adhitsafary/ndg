<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="{{ asset('asset/img/logo.png') }}" rel="icon">
    <title>Net Digital Group</title>
    <link href="{{ asset('template2/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('template2/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('template2/css/ruang-admin.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


    <link rel="stylesheet" href="{{ asset('template2/css/ruang-admin.min.css') }}">

</head>

<body id="page-top">
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/dashboard-pelanggan">
                <div class="sidebar-brand-icon">
                    <img src="{{ asset('asset/img/logo.png') }}">
                </div>
                <div class="sidebar-brand-text mx-3">Net Digital Group</div>
            </a>
            <hr class="sidebar-divider my-0">
            <li class="nav-item active">
                <a class="nav-link" href="/dashboard-pelanggan">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>


            <li class="nav-item">
                <a class="nav-link collapsed" href="/pembayaran/mudah" data-toggle="collapse"
                    data-target="#collapseBootstrap1000" aria-expanded="true" aria-controls="collapseBootstrap1000">
                    <img src="{{ asset('asset/img/bayar_baru.png') }}" alt="Gambar Pelanggan"
                        style="width: 30px; height: auto; margin-left: 10px;" class="mr-2">
                    <span class="font-weight-bold " style="color: black">Pembayaran</span>
                </a>
                <div id="collapseBootstrap1000" class="collapse" aria-labelledby="collapseBootstrap1000"
                    data-parent="#accordionSidebar">
                    <a href="{{ route('pelanggan.bukti', ['id' => $pelanggan->id_plg]) }}"
                        class="btn btn-sm btn-primary d-inline-block mt-2"
                        style="text-decoration: none; width: auto;">Bukti Pembayaran</a>

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
                                            placeholder="Cari Pelanggan" aria-label="Search"
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
                                                <img class="rounded-circle"
                                                    src="{{ asset('template2/img/man.png') }}"
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
                        @php
                            use Illuminate\Support\Facades\Session;
                            use App\Models\Pelanggan;

                            $pelangganId = Session::get('pelanggan_id');
                            $pelanggan = $pelangganId ? Pelanggan::where('id_plg', $pelangganId)->first() : null;
                        @endphp

                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                <div class="d-flex flex-column align-items-center">
                                    {{-- Foto pelanggan, fallback ke default --}}
                                    <img src="{{ asset($pelanggan && $pelanggan->foto ? $pelanggan->foto : 'asset/img/user/user.png') }}"
                                        alt="Foto Pelanggan"
                                        style="max-width: 40px; max-height: 40px; border-radius: 10%;">

                                    {{-- Nama pelanggan di bawah foto --}}
                                    <div class="text-white small ">
                                        @if ($pelanggan)
                                            Halo, {{ $pelanggan->nama_plg }}!
                                        @else
                                            Silakan login
                                        @endif
                                    </div>
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
                                <form action="{{ route('logout.pelanggan') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item" type="submit">
                                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </li>


                    </ul>
                </nav>

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
        <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>


        <!-- Bootstrap 5 CSS -->

        <!-- Bootstrap 5 JS Bundle (termasuk Popper.js) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}">
        </script>


    </div>


</body>

</html>
