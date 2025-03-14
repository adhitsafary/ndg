@extends('superadmin.layout_superadmin')

@section('konten')
    <div class="card m-5">


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

        <h3 class="text text-center text-black mt-3"> Edit Data Pembayaran : {{ $pembayaran->nama_plg }} </h3>
        <form action="{{ route('pembayaran.update', $pembayaran->id) }}" method="POST">
            @csrf
            @method('PUT')

            <label for="paket_plg">Paket Pelanggan</label>
            <input type="text" name="paket_plg" value="{{ old('paket_plg', $pembayaran->paket_plg) }}"
                class="form-control mt-2">

            <label for="jumlah_pembayaran">Jumlah Pembayaran</label>
            <input type="text" name="jumlah_pembayaran"
                value="{{ old('jumlah_pembayaran', $pembayaran->jumlah_pembayaran) }}" class="form-control mt-2">

            <label for="metode_transaksi">Metode Transaksi</label>
            <input type="text" name="metode_transaksi"
                value="{{ old('metode_transaksi', $pembayaran->metode_transaksi) }}" class="form-control mt-2">

            <label for="keterangan_plg">Keterangan Pelanggan</label>
            <input type="text" name="keterangan_plg" value="{{ old('keterangan_plg', $pembayaran->keterangan_plg) }}"
                class="form-control mt-2">

            <label for="created_at">Tanggal</label>
            <input type="datetime-local" name="created_at"
                value="{{ old('created_at', $pembayaran->created_at->format('Y-m-d\TH:i')) }}" class="form-control mt-2">

            <label for="tanggal_pembayaran">Bayar Untuk Bulan</label>
            <input type="text" name="tanggal_pembayaran"
                value="{{ old('tanggal_pembayaran', \Carbon\Carbon::parse($pembayaran->tanggal_pembayaran)->format('Y-m-d')) }}"
                class="form-control mt-2">





            <button class="btn btn-primary btn-sm mt-4">Simpan</button>
        </form>
    </div>




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
