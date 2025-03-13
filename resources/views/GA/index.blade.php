@extends($layout)

@section('konten')
    <div class="card ml-5 mr-5">
        <h2>Dashboard GA</h2>

        @if (session('error'))
            <div class="alert alert-danger" style="background: #a72828; color: white; border: 1px solid #ff0000;">
                {{ session('error') }}
            </div>
        @endif

        @if (session('alert'))
            <div class="alert  alert-dismissible fade show" role="alert"
                style="background: #a72828; color: white; border: 1px solid #ff0000;">
                {{ session('alert') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-dismissible fade show" role="alert"
                style="background: #28a745; color: white; border: 1px solid #218838;">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Modal Loading -->
        <div class="modal fade" id="loadingModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-center">
                    <div class="modal-body">
                        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden"></span>
                        </div>
                        <p class="mt-3">Sedang Memproses Data...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Sukses -->
        <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="successModalLabel">
                            <span class="me-2">✅</span> Berhasil!
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <h3 class="text-success">✔</h3>
                        <p id="successMessage" class="mt-2"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Error -->
        <div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">
                            <span class="me-2">❌</span> Gagal!
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <h3 class="text-danger">✖</h3>
                        <p id="errorMessage" class="mt-2"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-md-4 mb-3" style="height: 600px">
                <div class="card card-biru_tua h-100 p-3">
                    <div class="card-header bg-primary text-light" style="font-weight: 600">Persentase Pembayaran (Harian)
                    </div>
                    <br>

                    <div class="chart-area">
                        <canvas id="myPieChart"></canvas>
                    </div>
                    <br>
                    <div class="mt-3 d-flex justify-content-between ml-2 mr-2">
                        <div class="card bg-primary py-3 text-white text-center w-80" style="width: 4cm">
                            <h6 class="font-weight-bold">Total Tagihan</h6>
                            <p class="h6 mb-0 font-weight-bold">Rp {{ number_format($totalTagihanHariIni, 0, ',', '.') }}
                                <br> User: {{ $jumlahPelangganMembayarHariIni }}
                            </p>
                        </div>
                        <div class="card bg-success py-3 text-white text-center w-80" style="width: 4cm">
                            <h6 class="font-weight-bold">Tertagih</h6>
                            <p class="h6 mb-0 font-weight-bold">Rp
                                {{ number_format($totalPendapatanharian_semua, 0, ',', '.') }} <br> User:
                                {{ $totalUserHarian_semua }}</p>
                        </div>
                        <div class="card bg-danger py-3 text-white text-center w-80" style="width: 4cm">
                            <h6 class="font-weight-bold">Sisa Tagihan</h6>
                            <p class="h6 mb-0 font-weight-bold">Rp {{ number_format($totalTagihanTertagih, 0, ',', '.') }}
                                <br> User: {{ $totalUserTertagih }}
                            </p>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-3" style="height: 600px">
                    <div class="card-header bg-primary text-light" style="font-weight: 600">Perbaikan Proses :
                        {{ $total_perbaikan }}</div>
                    <div class="card-body">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Alamat</th>
                                    <th>Tanggal</th>
                                    <th>Porses</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($perbaikakn_tampil as $item)
                                    <tr>
                                        <td class="text-center" style="font-size:12; font-weight: bold">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>{{ $item->nama_plg }}</td>
                                        <td>{{ $item->alamat_plg }}</td>
                                        <td>{{ $item->created_at }}</td>
                                        <td>
                                            @if ($item->status == 'Proses')
                                                <form action="{{ route('perbaikan.selesai', $item->id) }}" method="POST"
                                                    class="d-inline-block"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menyelesaikan perbaikan ini?')">
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn btn-success btn-sm">Selesaikan</button>
                                                </form>
                                            @endif
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center" style="font-weight: 600">Tidak Ada
                                            Perbaikan
                                            Hari ini</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-3" style="height: 600px">
                    <div class="card-header bg-primary text-light" style="font-weight: 600">Pemasangan Proses :
                        {{ $totalPSB }}</div>
                    <div class="card-body ">
                        <table class="table table-sm table-bordered text-sm">
                            <thead>
                                <tr>
                                    <th class="p-1">No</th>
                                    <th class="p-1">Nama</th>
                                    <th class="p-1">Alamat</th>
                                    <th class="p-1">Tanggal</th>
                                    <th class="p-1">Proses</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($PSB_tampil as $item)
                                    <tr>
                                        <td class="text-center" style="font-size:12; font-weight: bold">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->alamat }}</td>
                                        <td>{{ $item->created_at }}</td>
                                        <td>
                                            @if ($item->status == 'Proses')
                                                <form action="{{ route('psb.selesai', $item->id) }}" method="POST"
                                                    class="d-inline-block"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menyelesaikan PSB ini?')">
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn btn-success btn-sm">Selesaikan</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center"
                                            style="font-size:12px; font-weight: bold;">
                                            Tidak Ada Pemasangan Hari ini</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <div class="row mt-3">
            <!-- Card 1 -->
            <div class="col-md-4">
                <div class="card mb-3" style="height: 600px">
                    <div class="card-header bg-primary text-light" style="font-weight: 600">Absensi</div>
                    <div class="card-body">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($absensi->groupBy('nama') as $nama => $items)
                                    <tr>
                                        <td class="text-center" style="font-size:12; font-weight: bold">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>{{ $nama }}</td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <!-- Card 2 (Duplikasi untuk tampilan 3 sejajar) -->
            <div class="col-md-4 mb-3 ">
                <div class="card card-biru_tua h-200 p-3" style="height: 600px">
                    <div class="card-header bg-primary text-light" style="font-weight: 600">Stok Barang</div>

                    <div class="">
                        @php
                            $groupedInventories = $inventories->groupBy('kategori');
                            $totalKeseluruhan = $inventories->sum('jml_brg');
                        @endphp
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered text-sm">
                                <thead class="table text-black">
                                    <tr>
                                        <th class="p-1">No</th>
                                        <th class="p-1">Kategori</th>
                                        <th class="p-1">Persentase (%)</th>
                                        <th class="p-1">Total Stok</th>
                                        <th class="p-1">Harga Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($groupedInventories as $kategori => $items)
                                        @php
                                            $totalJumlahKategori = $items->sum('jml_brg');
                                            $totalHargaKategori = $items->sum('harga_total');
                                            $persentaseKategori =
                                                $totalKeseluruhan > 0
                                                    ? round(($totalJumlahKategori / $totalKeseluruhan) * 100)
                                                    : 0;
                                        @endphp
                                        <tr>
                                            <td class="p-1">{{ $loop->iteration }}</td> <!-- Tambahan Nomor Urut -->
                                            <td class="p-1">{{ $kategori }}</td>
                                            <td class="p-1 text-center">{{ $persentaseKategori }}%</td>
                                            <td class="p-1 text-center">{{ $totalJumlahKategori }}
                                                {{ $items->first()->satuan ?? 'PCS' }}</td>
                                            <td class="p-1 text-right">
                                                Rp{{ number_format($totalHargaKategori, 2, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th class="p-1 text-center" colspan="2">Keseluruhan</th>
                                        <th class="p-1 text-center">100%</th>
                                        <td class="p-1 text-center">{{ $inventories->sum('jml_brg') }}
                                            {{ $inventories->first()->satuan ?? 'PCS' }}</td>
                                        <th class="p-1 text-right">
                                            Rp{{ number_format($inventories->sum('harga_total'), 2, ',', '.') }}</th>
                                    </tr>
                                </tfoot>
                            </table>


                        </div>
                    </div>
                </div>
            </div>


            <!-- Card 3 -->
            <div class="col-md-4">
                <div class="card mb-3" style="height: 600px">
                    <div class="card-header bg-primary text-light" style="font-weight: 600">Work Order :
                        {{ $total_WO }}</div>
                    <div class="card-body">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Alamat</th>
                                    <th>Tanggal</th>
                                    <th>Porses</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($Wo_tampil as $item)
                                    <tr>
                                        <td class="text-center" style="font-size:12; font-weight: bold">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>{{ $item->nama_plg }}</td>
                                        <td>{{ $item->alamat_plg }}</td>
                                        <td>{{ $item->created_at }}</td>
                                        <td>
                                            @if ($item->status == 'Proses')
                                                <form action="{{ route('perbaikan.selesai', $item->id) }}" method="POST"
                                                    class="d-inline-block"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menyelesaikan perbaikan ini?')">
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn btn-success btn-sm">Selesaikan</button>
                                                </form>
                                            @endif
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center" style="font-weight: 600">Tidak Ada
                                            Perbaikan
                                            Hari ini</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div> <br><br>



    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Data untuk Pie Chart
        var totalTagihanHariIni = @json($totalTagihanHariIni); // Total tagihan hari ini
        var totalPendapatanharian_semua = @json($totalPendapatanharian_semua); // Total pendapatan harian semua

        // Inisialisasi Pie Chart
        var ctx2 = document.getElementById("myPieChart").getContext('2d');
        var myPieChart = new Chart(ctx2, {
            type: 'pie', // Menggunakan tipe pie untuk lingkaran penuh
            data: {
                datasets: [{
                    data: [totalTagihanHariIni - totalPendapatanharian_semua,
                        totalPendapatanharian_semua
                    ], // Data dari controller
                    backgroundColor: ['#e74c3c', '#00b00c'], // Warna untuk bagian chart
                    hoverBackgroundColor: ['#c0392b', '#00b00c'], // Warna saat di-hover
                    hoverBorderColor: "rgba(234, 236, 244, 1)", // Border saat di-hover
                }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    titleFontColor: "white", // Judul tooltip putih
                    bodyFontColor: "white", // Isi tooltip putih
                    titleFontStyle: "bold", // Judul tooltip bold
                    bodyFontStyle: "bold", // Isi tooltip bold
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var dataset = data.datasets[tooltipItem.datasetIndex];
                            var total = dataset.data.reduce(function(previousValue, currentValue) {
                                return previousValue + currentValue;
                            });
                            var currentValue = dataset.data[tooltipItem.index];
                            var percentage = Math.floor(((currentValue / total) * 100) + 0.5);
                            return data.labels[tooltipItem.index] + ': ' + percentage + '%';
                        }
                    }
                },
                legend: {
                    display: true, // Tampilkan legenda untuk menjelaskan chart
                    position: 'bottom', // Posisi legenda di bawah chart
                    labels: {
                        fontColor: "white", // Warna teks legenda menjadi putih
                        fontStyle: "bold", // Teks legenda menjadi bold
                        usePointStyle: true // Menjaga ikon lingkaran di legend
                    }
                },
                cutoutPercentage: 0, // Tidak ada ruang di tengah lingkaran (untuk pie chart penuh)
                plugins: {
                    labels: {
                        render: 'label',
                        fontColor: 'white', // Membuat label chart menjadi putih
                        fontStyle: 'bold' // Membuat teks label chart menjadi bold
                    }
                }
            }
        });
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));

            // Tampilkan modal loading terlebih dahulu
            loadingModal.show();

            // Tunggu sebentar sebelum menampilkan modal sukses atau error
            setTimeout(function() {
                loadingModal.hide(); // Sembunyikan modal loading

                @if (session('success'))
                    document.getElementById("successMessage").innerText = "✅ {{ session('success') }}";
                    var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                    successModal.show();

                    // Tutup modal sukses setelah 3 detik
                    setTimeout(function() {
                        successModal.hide();
                    }, 3000);
                @endif

                @if (session('error'))
                    document.getElementById("errorMessage").innerText = "❌ {{ session('error') }}";
                    var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                    errorModal.show();

                    // Tutup modal error setelah 3 detik
                    setTimeout(function() {
                        errorModal.hide();
                    }, 3000);
                @endif
            }, 1500); // Delay 1.5 detik untuk efek loading
        });
    </script>
@endsection
