<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perbaikan Harian</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            background : linear-gradient(to right, #616161, rgb(0, 0, 151))
        }
    </style>
</head>

<body>
    <br><br><br>
    <div class="container mt-5">
        <div class="card shadow-lg ">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0 fw-bold">Perbaikan NDG</h2>
            </div>
            <div class="card-body">
                <p class="fw-bold text-primary">Total Perbaikan: <strong>{{ $total_perbaikan }}</strong></p>

                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="fw-bold">No</th>
                                <th class="fw-bold">Nama</th>
                                <th class="fw-bold">Alamat</th>
                                <th class="fw-bold">Indikasi</th>

                                <th class="fw-bold">Teknisi</th>
                                <th class="fw-bold">Tanggal</th>
                                <th class="fw-bold">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($perbaikan_limited as $item)
                                <tr>
                                    <td class="text-center fw-bold ">
                                        {{ $loop->iteration }}</td>
                                    <td class="fw-bold ">{{ $item->nama_plg }}</td>
                                    <td class="fw-bold ">{{ $item->alamat_plg }}</td>
                                    <td class="fw-bold ">{{ $item->keterangan }}</td>

                                    <td class="fw-bold ">{{ $item->teknisi }}</td>
                                    <td class="fw-bold ">{{ $item->created_at }}</td>
                                    <td>
                                        @if ($item->status == 'Proses')
                                            <form action="{{ route('perbaikan.selesai', $item->id) }}" method="POST"
                                                class="d-inline-block">
                                                @csrf
                                                <div class="progress"
                                                    style="width:200px; height:20px; border:1px solid #000; margin-top:5px; text-align:center; background:lightgray; cursor:pointer;"
                                                    data-completed="false">
                                                    <div class="progress-bar"
                                                        style="width:0%; background:red; color:white;">0%</div>
                                                </div>
                                            </form>
                                        @endif


                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" <td class="text-center fw-bold"> Tidak ada
                                        Perbaikan Hari ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    setTimeout(() => {
        window.location.href = "{{ url('/home/jam/') }}";
    }, 10000); // 5000 ms = 5 detik
</script>
//

<script>
    function startDownload(progressBar) {
        let width = 0;
        let increasing = true;
        let colors = ['red', 'orange', 'blue ', 'pink ', 'green', 'green'];
        let colorIndex = 0;

        let interval = setInterval(function() {
            if (width >= 100) {
                increasing = false;
            } else if (width <= 0) {
                increasing = true;
            }

            width += increasing ? 2 : -2;
            progressBar.style.width = width + '%';
            progressBar.innerText = width + '%';
            progressBar.style.background = colors[colorIndex];

            if (width % 20 === 0) {
                colorIndex = (colorIndex + 1) % colors.length;
            }
        }, 100);
    }

    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.progress').forEach(progressBar => {
            let form = progressBar.closest('form');
            progressBar.style.display = 'block';
            startDownload(progressBar.querySelector('.progress-bar'));

            progressBar.addEventListener('click', function() {
                form.submit();
            });
        });
    });
</script>


</html>
