@extends($layout)

@section('konten')
    <div class="ml-5 mr-5 mt-3">
        <div class="card shadow-lg p-3">
            <h3 style="font-weight: 1000">Daftar Inventory</h3>
            <div class="mb-3">
                <a href="{{ route('inventory.create') }}" class="btn btn-primary btn-sm">Tambah Barang +</a>
            </div>

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

            @php
                $groupedInventories = $inventories->groupBy('kategori');
                $totalKeseluruhan = $inventories->sum('jml_brg');
            @endphp
            <div class="table-responsive">
                <table class="table mt-3 table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Kategori</th>
                            <th>Persentase (%)</th>
                            <th>Total Stok</th>
                            <th>Harga Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($groupedInventories as $kategori => $items)
                            @php
                                $totalJumlahKategori = $items->sum('jml_brg');
                                $totalHargaKategori = $items->sum('harga_total');
                                $persentaseKategori =
                                    $totalKeseluruhan > 0 ? round(($totalJumlahKategori / $totalKeseluruhan) * 100) : 0;
                            @endphp
                            <tr>
                                <td>{{ $kategori }}</td>
                                <td>{{ $persentaseKategori }}%</td>
                                <td>{{ $totalJumlahKategori }} PCS/METER</td>
                                <td>Rp{{ number_format($totalHargaKategori, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th>Total Keseluruhan</th>
                            <th>100%</th>
                            <th>{{ $totalKeseluruhan }} PCS/METER</th>
                            <th>Rp{{ number_format($inventories->sum('harga_total'), 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>


            @foreach ($groupedInventories as $kategori => $items)
                @php
                    $totalJumlahKategori = $items->sum('jml_brg');
                    $persentaseKategori =
                        $totalKeseluruhan > 0 ? round(($totalJumlahKategori / $totalKeseluruhan) * 100) : 0;
                @endphp

                <h5 class="mt-4">Kategori: {{ $kategori }} ({{ $persentaseKategori }}%)</h5>
                <div class="progress mb-2" style="height: 20px;">
                    <div class="progress-bar" role="progressbar" style="width: {{ $persentaseKategori }}%;">
                        {{ $persentaseKategori }}%
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table mt-3 table-bordered">
                        <thead class="table-primary">
                            <tr>
                                <th>Nama</th>
                                <th>Jumlah</th>
                                <th>Satuan</th>
                                <th>Harga Satuan</th>
                                <th>Harga Total</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalJumlah = 0;
                                $totalHarga = 0;
                            @endphp
                            @foreach ($items as $inventory)
                                <tr>
                                    <td>{{ $inventory->nm_brg }}</td>
                                    <td>{{ $inventory->jml_brg }}</td>
                                    <td>{{ $inventory->satuan }}</td>
                                    <td>Rp{{ number_format($inventory->harga_satuan, 2) }}</td>
                                    <td>Rp{{ number_format($inventory->harga_total, 2) }}</td>
                                    <td>
                                        <div class="d-flex flex-wrap">
                                            <a href="{{ route('inventory.edit', $inventory->id) }}"
                                                class="btn btn-warning btn-sm me-1">Edit</a>
                                            <form action="{{ route('inventory.destroy', $inventory->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @php
                                    $totalJumlah += $inventory->jml_brg;
                                    $totalHarga += $inventory->harga_total;
                                @endphp
                            @endforeach
                            <tr class="fw-bold bg-light">
                                <td>Total Stok</td>
                                <td>{{ $totalJumlah }} PCS/METER</td>
                                <td></td>
                                <td></td>
                                <td>Rp{{ number_format($totalHarga, 2) }}</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));

            // Tampilkan modal loading terlebih dahulu
            loadingModal.show();

            // Tunggu sebentar sebelum menampilkan modal sukses/error atau alert
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
