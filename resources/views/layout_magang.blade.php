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
    <link rel="stylesheet" href="{{ asset('template2/css/ruang-admin.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">


</head>

<body id="page-top">
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/masuk/superadmin">
                <div class="sidebar-brand-icon">
                    <img src="{{ asset('asset/img/logo.png') }}">
                </div>
                <div class="sidebar-brand-text mx-3">Net Digital Group</div>
            </a>
            <hr class="sidebar-divider my-0">
            <li class="nav-item active">
                <a class="nav-link" href="/masuk/superadmin">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
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

                @yield('konten')
                <!-- Footer -->
            </div>

            <nav class="navbar navbar-dark bg-primary navbar-expand d-md-none d-lg-none d-xl-none fixed-bottom">
                <ul class="navbar-nav nav-justified w-100">
                    <li class="nav-item">
                        <a href="/masuk/superadmin" class="nav-link text-white">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="" class="nav-link text-white">absen</a>
                    </li>
                    <a href="{{ route('profile') }}" class="nav-link text-white">Profile</a>
                    </li>
                </ul>
            </nav>
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
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}">
        </script>
    </div>
</body>

</html>
