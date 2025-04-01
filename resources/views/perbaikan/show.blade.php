@extends($layout)

@section('konten')
    <div class="container mt-4">
        <div class="card">

            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <div style="font-weight: 700">Detail Perbaikan {{ $perbaikan->nama_plg }}</div>
                <div>
                    <button onclick="printPage()" class="btn btn-light btn-sm no-print">🖨️ Print</button>
                    <a href="{{ route('perbaikan.print', $perbaikan->id) }}" class="btn btn-sm btn-light">🚚 Surat Jalan</a>
                </div>

            </div>
            <div class="card-body" id="printableArea">
                <table class="table table-bordered">
                    <tr>
                        <th>Tiket</th>
                        <td>{{ $perbaikan->kd_tiket }}</td>
                    </tr>
                    <tr>
                        <th>ID Pelanggan</th>
                        <td>{{ $perbaikan->id_plg }}</td>
                    </tr>
                    <tr>
                        <th>Nama Pelanggan</th>
                        <td>{{ $perbaikan->nama_plg }}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>{{ $perbaikan->alamat_plg }}</td>
                    </tr>
                    <tr>
                        <th>No HP</th>
                        <td>{{ $perbaikan->no_telepon_plg }}</td>
                    </tr>
                    <tr>
                        <th>Paket</th>
                        <td>{{ $perbaikan->paket_plg }}</td>
                    </tr>
                    <tr>
                        <th>ODP</th>
                        <td>{{ $perbaikan->odp }}</td>
                    </tr>
                    <tr>
                        <th>Maps</th>
                        <td><a href="{{ $perbaikan->maps }}" target="_blank">Lihat di Maps</a></td>
                    </tr>
                    <tr>
                        <th>Teknisi</th>
                        <td>
                            @php
                                $teknisiList = json_decode($perbaikan->teknisi, true);
                            @endphp
                            {{ is_array($teknisiList) ? implode(', ', $teknisiList) : $perbaikan->teknisi }}
                        </td>
                    </tr>
                    <tr>
                        <th>Gangguan</th>
                        <td>{{ $perbaikan->keterangan }}</td>
                    </tr>
                    <tr>
                        <th>Keterangan</th>
                        <td>{{ $perbaikan->info }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ ucfirst($perbaikan->status) }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td>{{ $perbaikan->created_at }}</td>
                    </tr>
                    <tr>
                        <th>Total Biaya</th>
                        <td>Rp {{ number_format($perbaikan->total_biaya, 0, ',', '.') }}</td>
                    </tr>
                </table>

                <h5 class="mt-4">Inventory Keluar</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Harga Satuan</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalKeseluruhan = 0;
                        @endphp

                        @if (!empty($inventory_keluar) && is_array($inventory_keluar))
                            @foreach ($inventory_keluar as $nama_barang => $barang)
                                @php
                                    $subtotal = ($barang['jml_brg'] ?? 0) * ($barang['harga_satuan'] ?? 0);
                                    $totalKeseluruhan += $subtotal;
                                @endphp
                                <tr>
                                    <td>{{ $nama_barang }}</td>
                                    <td>{{ $barang['jml_brg'] ?? '-' }}</td>
                                    <td>Rp {{ number_format($barang['harga_satuan'] ?? 0, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        @endif

                        <tr>
                            <th colspan="3" class="text-end">Total Keseluruhan:</th>
                            <th>Rp {{ number_format($totalKeseluruhan, 0, ',', '.') }}</th>
                        </tr>
                    </tbody>
                </table>

                <!-- TANDA TANGAN -->
                <div class="row mt-5">
                    <div class="col text-center">
                        <p>Teknisi</p>
                        <br><br>
                        <p>( ______________________ )</p>
                    </div>
                    <div class="col text-center">
                        <p>Admin</p>
                        <br><br>
                        <p>( ______________________ )</p>
                    </div>
                </div>

                <!-- Tombol Kembali (Tidak Dicetak) -->
                <a href="{{ route('perbaikan.index') }}" class="btn btn-secondary mt-3 no-print">Kembali</a>
            </div>
        </div>
    </div> <br><br><br>

    <!-- CSS untuk Menyembunyikan Elemen Saat Print -->
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
        }

        @media print {
            .card-header {
                background-color: #0d6efd !important;
                /* Warna bg-primary Bootstrap */
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>

    <script>
        function printPage() {
            window.print();
        }
    </script>


@endsection
