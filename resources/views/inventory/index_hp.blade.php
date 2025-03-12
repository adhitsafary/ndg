@extends($layout)

@section('konten')
<div class="container mt-3">
    <div class="card shadow-lg p-3">
        <h3 style="font-weight: 1000">Daftar Inventory</h3>
        <div class="mb-3">
            <a href="{{ route('inventory.create') }}" class="btn btn-primary btn-sm">Tambah Barang +</a>
        </div>

        <!-- Notifikasi -->
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if (session('alert'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('alert') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Modal Loading -->
        <div class="modal fade" id="loadingModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-center p-3">
                    <div class="modal-body">
                        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></div>
                        <p class="mt-2">Sedang Memproses...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Sukses -->
        <div class="modal fade" id="successModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">✅ Berhasil!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <h3 class="text-success">✔</h3>
                        <p id="successMessage"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Error -->
        <div class="modal fade" id="errorModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">❌ Gagal!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <h3 class="text-danger">✖</h3>
                        <p id="errorMessage"></p>
                    </div>
                </div>
            </div>
        </div>

        @php
            $groupedInventories = $inventories->groupBy('kategori');
            $totalKeseluruhan = $inventories->sum('jml_brg');
        @endphp

        @foreach ($groupedInventories as $kategori => $items)
            @php
                $totalJumlahKategori = $items->sum('jml_brg');
                $persentaseKategori = $totalKeseluruhan > 0 ? round(($totalJumlahKategori / $totalKeseluruhan) * 100) : 0;
            @endphp

            <h5 class="mt-4">Kategori: {{ $kategori }}</h5>
            <div class="progress mb-2" style="height: 20px;">
                <div class="progress-bar" role="progressbar" style="width: {{ $persentaseKategori }}%;">
                    {{ $persentaseKategori }}%
                </div>
            </div>

            <div class="table-responsive">
                <table class="table mt-3 table-bordered">
                    <thead class="table-dark">
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
                                        <a href="{{ route('inventory.edit', $inventory->id) }}" class="btn btn-warning btn-sm me-1">Edit</a>
                                        <form action="{{ route('inventory.destroy', $inventory->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
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
        // Tampilkan modal loading
        var loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
        loadingModal.show();

        // Setelah loading selesai, tampilkan modal sukses atau error
        setTimeout(function() {
            loadingModal.hide();

            @if (session('success'))
                document.getElementById("successMessage").innerText = "✅ {{ session('success') }}";
                var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();
            @endif

            @if (session('error'))
                document.getElementById("errorMessage").innerText = "❌ {{ session('error') }}";
                var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                errorModal.show();
            @endif
        }, 1500);
    });
</script>
@endsection
