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

    <!-- Style Utama -->
    <link href="{{ asset('template2/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('template2/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('template2/css/ruang-admin.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Background Lengkung -->
    <style>
        .half-circle-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 280px;
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            border-bottom-left-radius: 50% 100%;
            border-bottom-right-radius: 50% 100%;
            z-index: -1;
        }

        .content-container {
            position: relative;
            z-index: 1;
            padding-top: 60px;
        }

        .live-clock {
            color: #fff;
            font-weight: bold;
            font-size: 1.2rem;
        }
    </style>
</head>

<body id="page-top">
    <!-- Background setengah lingkaran -->
    <div class="half-circle-bg"></div>

    <div id="wrapper">
        <!-- Sidebar bisa ditaruh di sini -->

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content" class="content-container">

                <div class="d-flex flex-column align-items-center justify-content-center mb-3">
                    <h6 class="h6 text-center live-clock" id="liveClock"></h6>
                </div>

                @yield('konten')

            </div>
        </div>

        <!-- Scroll to top -->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <!-- Scripts -->
        <script>
            function updateClock() {
                const now = new Date();
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');
                const formattedTime = `${hours}:${minutes}:${seconds}`;
                document.getElementById('liveClock').textContent = formattedTime;
            }
            setInterval(updateClock, 1000);
            updateClock();
        </script>

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

        <!-- Midtrans -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}">
        </script>
    </div>
</body>

</html>
