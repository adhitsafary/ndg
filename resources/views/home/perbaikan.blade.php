<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perbaikan Harian</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
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
                                    <td class="fw-bold ">{{ $item->created_at }}</td>
                                      <td>
                                @if ($item->status == 'Proses')
                                    <!-- Hanya tampilkan tombol jika statusnya Proses -->
                                    <form action="{{ route('perbaikan.selesai', $item->id) }}" method="POST"
                                        class="d-inline-block">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Proses</button>
                                    </form>
                                @endif
                            </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" <td class="text-center fw-bold" > Tidak ada
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
    }, 15000); // 5000 ms = 5 detik
</script>

</html>
