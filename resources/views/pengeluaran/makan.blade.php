@extends($layout)

@section('konten')

    <div class="container my-4">
        <div class="card shadow">

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

            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Data Pengeluaran - {{ \Carbon\Carbon::now()->format('d M Y') }}</h4>
            </div>

            <div class="card-body">
                <form method="GET" action="{{ route('pengeluaran.makan') }}" class="mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="nama" class="form-label">Pilih Nama:</label>
                            <select name="nama" id="nama" class="form-select">
                                <option value="">-- Semua Orang --</option>
                                @foreach ($daftarNama as $n)
                                    <option value="{{ $n->nama }}" {{ request('nama') == $n->nama ? 'selected' : '' }}>
                                        {{ $n->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="kategori" class="form-label">Pilih Kategori:</label>
                            <select name="kategori" id="kategori" class="form-select">
                                <option value="">-- Semua Kategori --</option>
                                <option value="Makan" {{ request('kategori') == 'Makan' ? 'selected' : '' }}>Makan</option>
                                <option value="Bensin" {{ request('kategori') == 'Bensin' ? 'selected' : '' }}>Bensin
                                </option>
                                <option value="Parkir" {{ request('kategori') == 'Parkir' ? 'selected' : '' }}>Parkir
                                </option>
                                <option value="Lainnya" {{ request('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-success">Tampilkan</button>
                        </div>
                    </div>
                </form>

                @if ($orang->count())
                    <form action="{{ route('pengeluaran.simpan') }}" method="POST">
                        @csrf
                        <input type="hidden" name="kategori" id="kategori_hidden" value="{{ request('kategori') }}">
                        <input type="hidden" name="keterangan" id="keterangan_hidden"
                            value="{{ request('kategori') }}">
                        <div class="mb-3">
                            <label for="harga_satuan" class="form-label">Harga Satuan:</label>
                            <input type="number" name="harga_satuan" id="harga_satuan" class="form-control"
                                value="5000" required>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Pilih</th>
                                    <th>Nama</th>
                                    <th>Status</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orang as $index => $o)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><input type="checkbox" name="nama[]" value="{{ $o->nama }}"></td>
                                        <td>{{ $o->nama }}</td>
                                        <td>{{ $o->status ?? '-' }}</td>
                                        <td>{{ $o->created_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <button type="submit" class="btn btn-warning mt-2">Catat Pengeluaran</button>
                    </form>
                @else
                    <p class="text-muted">Tidak ada data hari ini.</p>
                @endif
            </div>
            <div class="card-body " id="print-area">

                @if ($pengeluaranHariIni->count())
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5>Rekap Pengeluaran - {{ \Carbon\Carbon::now()->format('d M Y') }}</h5>
                        <button onclick="printRekap()" class="btn btn-sm btn-outline-primary">🖨 Cetak</button>
                    </div>

                    <div>
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Keterangan</th>
                                    <th>Deskripsi</th>
                                    <th>Harga Satuan</th>
                                    <th>Jumlah</th>
                                    <th>Total</th>
                                    <th>Kategori</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalHarian = 0; @endphp
                                @foreach ($pengeluaranHariIni as $index => $p)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $p->keterangan }}</td>
                                        <td>{{ $p->deskripsi ?? '-' }}</td>
                                        <td>Rp{{ number_format($p->harga_satuan, 0, ',', '.') }}</td>
                                        <td>{{ $p->volume }}</td>
                                        <td>Rp{{ number_format($p->harga_total, 0, ',', '.') }}</td>
                                        <td>{{ $p->kategori }}</td>
                                        <td>{{ $p->created_at }}</td>
                                    </tr>
                                    @php $totalHarian += $p->harga_total; @endphp
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-success">
                                    <th colspan="5" class="text-end">Total Pengeluaran Hari Ini</th>
                                    <th colspan="3">Rp{{ number_format($totalHarian, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    {{-- Script untuk Print --}}
                @else
                    <p class="text-muted mt-4">Belum ada pengeluaran yang tercatat hari ini.</p>
                @endif


                @if ($cashHariIni->count())
                    <div class="mt-5">
                        <h5>Transaksi CASH - {{ \Carbon\Carbon::now()->format('d M Y') }}</h5>

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Alamat</th>

                                    <th>Jumlah Bayar</th>
                                    <th>Untuk Bulan</th>

                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalCash = 0; @endphp
                                @foreach ($cashHariIni as $i => $c)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $c->nama_plg }}</td>
                                        <td>{{ $c->alamat_plg }}</td>

                                        <td>Rp{{ number_format($c->jumlah_pembayaran, 0, ',', '.') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($c->tanggal_pembayaran)->translatedFormat('F Y') }}
                                        </td>

                                        <td>{{ $c->created_at }}</td>
                                    </tr>
                                    @php $totalCash += $c->jumlah_pembayaran; @endphp
                                @endforeach
                            </tbody>
                            <tfoot class="text-center">
                                <tr class="table-success">
                                    <th colspan="4" class="text-end">Total CASH Hari Ini</th>
                                    <th colspan="4">Rp{{ number_format($totalCash, 0, ',', '.') }}</th>
                                </tr>
                                <tr class="table-success mt-4 text-center">
                                    <th colspan="4" class="text-end">Pengeluaran - CASH = Total</th>
                                    <th class="text-end" colspan="8">
                                        {{ number_format($totalHarian, 0, ',', '.') }} -
                                        {{ number_format($totalCash, 0, ',', '.') }} =
                                        {{ number_format($totalCash - $totalHarian, 0, ',', '.') }}
                                    </th>
                                </tr>
                            </tfoot>

                        </table>
                    </div>
                @else
                    <p class="text-muted mt-4">Belum ada transaksi CASH yang tercatat hari ini.</p>
                @endif

                @if ($rekapPemasanganHariIni->count())
                    <div class="mt-5">
                        <h5>Rekap Pemasangan - {{ \Carbon\Carbon::now()->format('d M Y') }}</h5>

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Alamat</th>
                                    <th>Paket</th>
                                    <th>Status</th>
                                    <th>Tanggal Aktivasi</th>
                                    <th>Marketing</th>
                                    <th>Regis</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalBiaya = 0; @endphp
                                @foreach ($rekapPemasanganHariIni as $i => $item)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->alamat }}</td>
                                        <td>{{ $item->paket_plg }}</td>
                                        <td>{{ $item->status }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tgl_aktivasi)->translatedFormat('d M Y') }}
                                        </td>
                                        <td>{{ $item->marketing }}</td>
                                        <td>{{ $item->registrasi }}</td>
                                    </tr>
                                    @php $totalBiaya += $item->registrasi; @endphp
                                @endforeach
                            </tbody>
                            <tfoot class="text-center">
                                <tr class="table-success">
                                    <th colspan="7" class="text-end">Total Biaya Pemasangan Hari Ini</th>
                                    <th colspan="2">Rp{{ number_format($totalBiaya, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <p class="text-muted mt-4">Belum ada pemasangan yang tercatat hari ini.</p>
                @endif


                @if ($tfHariIni->count())
                    <div class="mt-5">
                        <h5>Transaksi TF - {{ \Carbon\Carbon::now()->format('d M Y') }}</h5>

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Alamat</th>
                                    <th>Jumlah Bayar</th>
                                    <th>Untuk Bulan</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalTf = 0; @endphp
                                @foreach ($tfHariIni as $i => $c)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $c->nama_plg }}</td>
                                        <td>{{ $c->alamat_plg }}</td>
                                        <td>Rp{{ number_format($c->jumlah_pembayaran, 0, ',', '.') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($c->tanggal_pembayaran)->translatedFormat('F Y') }}
                                        </td>
                                        <td>{{ $c->created_at }}</td>
                                    </tr>
                                    @php $totalTf += $c->jumlah_pembayaran; @endphp
                                @endforeach
                            </tbody>
                            <tfoot class="text-center">
                                <tr class="table-success">
                                    <th colspan="4" class="text-end">Total TF Hari Ini</th>
                                    <th colspan="2">Rp{{ number_format($totalTf, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <p class="text-muted mt-4">Belum ada transaksi TF yang tercatat hari ini.</p>
                @endif

                @if ($cashflow->isNotEmpty())
                    <div class="mt-5">
                        <h5>Cashflow Hari Ini - {{ \Carbon\Carbon::now()->format('d M Y') }}</h5>
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Debit (Pengeluaran)</th>
                                    <th>Credit (Pembayaran)</th>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalDebit = 0;
                                    $totalCredit = 0;
                                @endphp
                                @foreach ($cashflow as $i => $item)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td class="text-end">
                                            @if ($item->tipe == 'debit')
                                                @php $totalDebit += $item->jumlah; @endphp
                                                Rp{{ number_format($item->jumlah, 0, ',', '.') }}
                                            @else
                                                Rp0
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if ($item->tipe == 'credit')
                                                @php $totalCredit += $item->jumlah; @endphp
                                                Rp{{ number_format($item->jumlah, 0, ',', '.') }}
                                            @else
                                                Rp0
                                            @endif
                                        </td>
                                        <td>{{ $item->tanggal }}</td>
                                        <td>{{ $item->keterangan }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-success">
                                    <th colspan="2" class="text-end">Total</th>
                                    <th class="text-end">Rp{{ number_format($totalDebit, 0, ',', '.') }}</th>
                                    <th class="text-end">Rp{{ number_format($totalCredit, 0, ',', '.') }}</th>
                                    <th colspan="2"></th>
                                </tr>
                                <tr class="table-primary">
                                    <th colspan="2" class="text-end">Saldo Akhir</th>
                                    <th colspan="4" class="text-start">
                                        Rp{{ number_format($totalCredit - $totalDebit, 0, ',', '.') }}
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));

            console.log("Menampilkan modal loading...");
            loadingModal.show();

            setTimeout(function() {
                loadingModal.hide();
                console.log("Menutup modal loading...");

                @if (session('success'))
                    console.log("Menampilkan modal sukses...");
                    document.getElementById("successMessage").innerText = "✅ {{ session('success') }}";
                    var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                    successModal.show();
                    setTimeout(function() {
                        successModal.hide();
                        console.log("Menutup modal sukses...");
                    }, 3000);
                @endif

                @if (session('error'))
                    console.log("Menampilkan modal error...");
                    document.getElementById("errorMessage").innerText = "❌ {{ session('error') }}";
                    var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                    errorModal.show();
                    setTimeout(function() {
                        errorModal.hide();
                        console.log("Menutup modal error...");
                    }, 3000);
                @endif
            }, 1500);
        });

        document.getElementById('kategori').addEventListener('change', function() {
            const kategori = this.value;
            const hargaInput = document.getElementById('harga_satuan');
            const hiddenKategori = document.getElementById('kategori_hidden');
            const hiddenKeterangan = document.getElementById('keterangan_hidden');

            if (kategori === 'Makan') hargaInput.value = 5000;
            else if (kategori === 'Bensin') hargaInput.value = 15000;
            else if (kategori === 'Parkir') hargaInput.value = 2000;
            else hargaInput.value = 0;

            // Update hidden input
            hiddenKategori.value = kategori;
            hiddenKeterangan.value = kategori;
        });
    </script>

    <script>
        function printRekap() {
            var printContents = document.getElementById('print-area').innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload(); // refresh setelah print biar tampilan kembali
        }
    </script>

    <style>
        @media print {
            body {
                margin: 20mm;
            }

            #print-area {
                padding: 10mm;
                font-size: 12pt;
            }

            .btn,
            .alert,
            form,
            .card-header,
            .modal,
            nav,
            .navbar,
            .sidebar {
                display: none !important;
            }

            table {
                border-collapse: collapse !important;
                width: 100%;
            }

            table th,
            table td {
                border: 1px solid #000 !important;
                padding: 5px !important;
            }
        }
    </style>
@endsection
