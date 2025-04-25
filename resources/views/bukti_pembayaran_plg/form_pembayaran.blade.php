@extends('layout_user')

@section('konten')
<div class="container py-4">
    <h2 class="text-xl font-bold mb-4">Form Pembayaran</h2>

    <p><strong>Nama:</strong> {{ $pelanggan->nama_plg }}</p>
    <p><strong>Paket:</strong> {{ $pelanggan->paket_plg }}</p>
    <p><strong>Harga:</strong> Rp {{ number_format($pelanggan->harga_paket, 0, ',', '.') }}</p>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('store.pembayaran', $pelanggan->id_plg) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Bulan yang Dibayar:</label>
            <input type="month" name="tanggal_pembayaran" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Jumlah Pembayaran:</label>
            <input type="number" name="jumlah_pembayaran" class="form-control" required>
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
            <img src="{{ asset('asset/img/logo.png') }}" alt="QRIS" style="height:180px; width:180px;">
        </div>

        <div class="mb-3 mt-3">
            <label>Nama Pengirim:</label>
            <input type="text" name="nama_pengirim" class="form-control">
        </div>

        <div class="mb-3">
            <label>Upload Bukti Transfer (jika ada):</label>
            <input type="file" name="bukti_transfer" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Kirim Bukti Pembayaran</button>
    </form>
</div>
@endsection
