<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Pemasangan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>

<body>
    <br><br><br>
    <div class="container mt-5">
        <div class="card shadow-lg ">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0 fw-bold">Pemberitahuan</h2>
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered">
                        <thead class="table-primary">
                            <tr>
                                <th class="fw-bold text-center">No</th>
                                <th class="fw-bold">pesan</th>
                                <th class="fw-bold">Tanggal Pembuatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pemberitahuan as $index => $item)
                                <tr>
                                    <td class="fw-bold text-center">{{ $index + 1 }}</td>
                                    <td class="fw-bold ">{{ $item->pesan }}</td>
                                    <td class="fw-bold ">{{ $item->created_at }}</td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center fw-bold text-danger">Tidak ada data
                                        Pemberitahuan.</td>
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
    }, 15000); // 5000 ms = 5 detik
</script>

</html>
