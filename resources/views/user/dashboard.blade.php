@extends('layout_user')

@section('konten')
    <div class="mx-auto px-4 py-8">
        <div class="rounded-2xl p-6">
            <!-- Informasi Pelanggan -->
            <div class="card mb-4">
                <div class="card-body text-sm">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Informasi Pelanggan</h2>

                    <div class="mb-2">
                        <h5 class="font-semibold">ID Pelanggan</h5>
                        <p>{{ $pelanggan->id_plg }}</p>
                    </div>
                    <div class="mb-2">
                        <h5 class="font-semibold">Nama Pelanggan</h5>
                        <p>{{ $pelanggan->nama_plg }}</p>
                    </div>
                    <div class="mb-2">
                        <h5 class="font-semibold">Alamat</h5>
                        <p>{{ $pelanggan->alamat_plg }}</p>
                    </div>
                    <div class="mb-2">
                        <h5 class="font-semibold">No Telepon</h5>
                        <p>{{ $pelanggan->no_telepon_plg }}</p>
                    </div>
                    <div class="mb-2">
                        <h5 class="font-semibold">Paket</h5>
                        <p>{{ $pelanggan->paket_plg }}</p>
                    </div>
                    <div class="mb-2">
                        <h5 class="font-semibold">Harga Paket</h5>
                        <p>Rp {{ number_format($pelanggan->harga_paket, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <h5 class="font-semibold">Status Pembayaran</h5>
                        <span class="badge {{ $pelanggan->status_pembayaran == 'Lunas' ? 'bg-success' : 'bg-danger' }}">
                            {{ ucfirst($pelanggan->status_pembayaran) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Form Pembayaran --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow p-4 mt-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Form Pembayaran</h2>

                <form action="{{ route('store.pembayaran', $pelanggan->id_plg) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    @php
                        $bulanTerakhir = $riwayatBayar->max('tanggal_pembayaran');
                        $bulanSelanjutnya = $bulanTerakhir
                            ? \Carbon\Carbon::parse($bulanTerakhir)->addMonth()->format('Y-m')
                            : now()->format('Y-m');
                    @endphp

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Bulan yang Dibayar</label>
                            <input type="month" name="tanggal_pembayaran" class="form-control" required
                                value="{{ $bulanSelanjutnya }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jumlah Pembayaran</label>
                            <input type="number" name="jumlah_pembayaran" id="jumlah_pembayaran" class="form-control"
                                required value="{{ $pelanggan->harga_paket }}">
                        </div>

                        <div class="mb-3">
                            <label>Metode Pembayaran:</label>
                            <select name="metode_transaksi" class="form-control" required
                                onchange="
                                    document.getElementById('qrisBox').style.display = this.value === 'QRIS' ? 'block' : 'none';
                                    document.getElementById('bankBox').style.display = this.value === 'Transfer Bank' ? 'block' : 'none';
                                ">
                                <option value="">-- Pilih --</option>
                                <option value="Transfer Bank">Transfer Bank</option>
                                <option value="QRIS">QRIS</option>
                            </select>
                        </div>

                        <div id="bankBox" style="display: none;" class="mt-3">
                            <p><strong>Silakan transfer ke rekening berikut:</strong></p>
                            <ul>
                                <li>Bank BCA - 3770198576 a.n. Ruslandi</li>
                            </ul>
                        </div>

                        <div id="qrisBox" style="display: none;" class="text-center mt-3">
                            <p>Silakan scan QR berikut untuk melakukan pembayaran:</p>
                            <img src="{{ asset('asset/img/icon/qris.png') }}" alt="QRIS"
                                style="height:180px; width:180px;">
                        </div>

                        <div class="mb-3 mt-3">
                            <label>Nama Pengirim:</label>
                            <input type="text" name="nama_pengirim" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Upload Bukti Transfer :</label>
                            <input type="file" name="bukti_transfer" class="form-control" id="bukti_transfer_input"
                                onchange="previewBuktiTransfer(event)">
                        </div>

                        <!-- Preview Gambar -->
                        <div class="mb-3">
                            <img id="preview_bukti_transfer" src="#" alt="Preview Bukti Transfer"
                                style="display: none; max-height: 200px; border-radius: 10px;">
                        </div>

                        <script>
                            function previewBuktiTransfer(event) {
                                const input = event.target;
                                const preview = document.getElementById('preview_bukti_transfer');

                                if (input.files && input.files[0]) {
                                    const reader = new FileReader();

                                    reader.onload = function(e) {
                                        preview.src = e.target.result;
                                        preview.style.display = 'block';
                                    }

                                    reader.readAsDataURL(input.files[0]);
                                } else {
                                    preview.style.display = 'none';
                                }
                            }
                        </script>


                        <div class="mt-3 text-end">
                            <button type="submit" class="btn btn-success">Konfirmasi Pembayaran</button>
                        </div>

                    </div>
                    <br>
            </div>

            <br>

            {{-- Riwayat Pembayaran --}}
            <div class="card mt-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Riwayat Pembayaran</h2>
                <div class="row">
                    @foreach ($riwayatBayar as $bayar)
                        <div class="col-12 col-md-6 col-lg-4 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Riwayat Pembayaran</h5>
                                    <p class="text-muted">Tanggal Bayar:
                                        {{ \Carbon\Carbon::parse($bayar->created_at)->format('d M Y') }}</p>
                                    <p class="text-muted">Jumlah: Rp
                                        {{ number_format($bayar->jumlah_pembayaran, 0, ',', '.') }}</p>
                                    <p class="text-muted">Metode: {{ $bayar->metode_transaksi }}</p>
                                    <p class="text-muted">Pembayaran Untuk Bulan:
                                        {{ \Carbon\Carbon::parse($bayar->tanggal_pembayaran)->format('d M Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <br><br>
    @endsection
