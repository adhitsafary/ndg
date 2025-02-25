<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemasangan NDG</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>

<body>
    <br><br><br>
    <div class="container mt-5">
        <div class="card shadow-lg ">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0 fw-bold">Pemasangan NDG</h2>
            </div>
            <div class="card-body">
                <p class="fw-bold text-primary">Total Pemasangan: <strong>{{ $total_pemasangan }}</strong></p>

                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered">
                        <thead class="table-primary">
                            <tr>
                                <th class="fw-bold text-center">No</th>
                                <th class="fw-bold">Nama Pelanggan</th>
                                <th class="fw-bold">Tanggal Pengajuan</th>
                                <th class="fw-bold">Alamat</th>
                                <th class="fw-bold">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rekap_pemasangan_limited as $index => $pemasangan)
                                <tr>
                                    <td class="fw-bold text-center">{{ $index + 1 }}</td>
                                    <td class="fw-bold ">{{ $pemasangan->nama }}</td>
                                    <td class="fw-bold ">{{ $pemasangan->alamat }}</td>
                                    <td class="fw-bold ">{{ $pemasangan->tgl_pengajuan }}</td>
                                    <td>
                                @php
                                    // Cek apakah pelanggan sudah diaktivasi
                                    $isActivated = \App\Models\Pelanggan::where('id_plg', $pemasangan->id_plg)->exists();
                                @endphp

                                @if ($isActivated)
                                    <!-- Jika sudah diaktivasi, tampilkan ikon ceklis tidak bisa diklik -->
                                    <img src="{{ asset('asset/img/ceklis2.png') }}" alt="Sudah Aktivasi"
                                        style="width:45px; height:45px;">
                                @else
                                    <!-- Jika belum diaktivasi, tampilkan gambar x dan tombol ceklis untuk aktivasi -->
                                    <a href="{{ route('rekap_pemasangan.aktivasi', $pemasangan->id) }}"
                                        onclick="return confirm('Apakah Pelanggan Sudah Selesai Pasang?')">
                                        <img src="{{ asset('asset/img/x.png') }}" alt="Belum Aktivasi"
                                            style="width:40px; height:40px;">
                                    </a>
                                @endif
                            </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center fw-bold text-danger">Tidak ada data
                                        pemasangan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

<!-- <script>
    let pages = [
        "{{ url('/home/perbaikan/') }}",
        "{{ url('/home/pemberitahuan/') }}",
        "{{ url('/halaman-3') }}"
    ];

    let currentPage = window.location.href;
    let nextPage = pages[(pages.indexOf(currentPage) + 1) % pages.length];

    setTimeout(() => {
        window.location.href = nextPage;
    }, 5000);
</script> -->

<script>
    setTimeout(() => {
        window.location.href = "{{ url('/home/perbaikan/') }}";
    }, 10000); // 5000 ms = 5 detik
</script>



</html>
