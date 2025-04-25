@extends('layout_user')

@section('konten')
    <div class="mx-auto px-4 py-6">


        @if ($buktiPembayaran->isEmpty())
            <div class="alert alert-info text-black text-center">Belum ada bukti pembayaran.</div>
        @else
            <div class="card mt-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Bukti Pembayaran - {{ $pelanggan->nama_plg }}</h2>
                <br>
                <div class="row">
                    @foreach ($buktiPembayaran as $bukti)
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                            <div class="card shadow-sm h-100">
                                <div class="card-body">
                                    <p class="card-title">Nama Pengirim : {{ $bukti->nama_pengirim ?? '-' }}</p>
                                    <p class="text-muted mb-1"><strong>Tanggal Bayar:</strong><br>
                                        {{ \Carbon\Carbon::parse($bukti->tanggal_pembayaran)->format('d M Y') }}
                                    </p>
                                    <p class="text-muted mb-1"><strong>Jumlah:</strong> Rp
                                        {{ number_format($bukti->jumlah_pembayaran, 0, ',', '.') }}
                                    </p>
                                    <p class="text-muted mb-2"><strong>Metode:</strong> {{ $bukti->metode_transaksi }}</p>
                                    @if ($bukti->bukti_transfer)
                                        <a href="{{ asset($bukti->bukti_transfer) }}" target="_blank">
                                            <img src="{{ asset($bukti->bukti_transfer) }}" alt="Bukti Transfer"
                                                class="rounded w-100 img-fluid mt-2"
                                                style="max-height: 150px; object-fit: contain;">
                                        </a>
                                    @else
                                        <span class="text-muted">Tidak ada bukti</span>
                                    @endif

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
