@extends($layout)

@section('konten')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <div style="font-weight: 700">Detail Pemasangan {{ $rekap_pemasangan->nama }}</div>

                <a href="{{ route('rekap_pemasangan.print', $rekap_pemasangan->id) }}" class="btn btn-sm btn-light">🚚 Surat
                    Jalan</a>

            </div>
            <div class="card-body" id="printableArea">
                <table class="table table-bordered">
                    <tr>
                        <th>ID Pelanggan</th>
                        <td>{{ $rekap_pemasangan->id_plg }}</td>
                    </tr>
                    <tr>
                        <th>NIK</th>
                        <td>{{ $rekap_pemasangan->nik }}</td>
                    </tr>
                    <tr>
                        <th>Nama</th>
                        <td>{{ $rekap_pemasangan->nama }}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>{{ $rekap_pemasangan->alamat }}</td>
                    </tr>
                    <tr>
                        <th>No HP</th>
                        <td>{{ $rekap_pemasangan->no_telpon }}</td>
                    </tr>
                    <tr>
                        <th>Paket</th>
                        <td>{{ $rekap_pemasangan->paket_plg }}</td>
                    </tr>
                    <tr>
                        <th>harga</th>
                        <td>{{ $rekap_pemasangan->harga_paket }}</td>
                    </tr>
                    <tr>
                        <th>Jt</th>
                        <td>{{ $rekap_pemasangan->jt }}</td>
                    </tr>

                    <tr>
                        <th>tgl_pengajuan</th>
                        <td>{{ $rekap_pemasangan->tgl_pengajuan }}</td>
                    </tr>

                    <tr>
                        <th>Registrasi</th>
                        <td>{{ $rekap_pemasangan->registrasi }}</td>
                    </tr>
                    <tr>
                        <th>Marketing</th>
                        <td>{{ $rekap_pemasangan->marketing }}</td>
                    </tr>
                    <tr>
                        <th>SN Modem</th>
                        <td>{{ $rekap_pemasangan->sn_modem }}</td>
                    </tr>

                    <tr>
                        <th>ODP</th>
                        <td>
                            @php
                                $odpList = json_decode($rekap_pemasangan->odp, true);
                            @endphp
                            {{ is_array($odpList) ? implode(', ', $odpList) : $rekap_pemasangan->odp }}
                        </td>
                    </tr>
                    <tr>
                        <th>Keterangan</th>
                        <td>{{ $rekap_pemasangan->keterangan_plg }}</td>
                    </tr>



                    <tr>
                        <th>Teknisi</th>
                        <td>{{ $rekap_pemasangan->teknisi }}</td>
                    </tr>
                    <tr>
                        <th>Biaya</th>
                        <td>{{ $rekap_pemasangan->biaya }}</td>
                    </tr>

                    <tr>
                        <th>Maps</th>
                        <td><a href="{{ $rekap_pemasangan->maps }}" target="_blank">Lihat di Maps</a></td>
                    </tr>


                    <tr>
                        <th>Status</th>
                        <td>{{ ucfirst($rekap_pemasangan->status) }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td>{{ $rekap_pemasangan->created_at }}</td>
                    </tr>
                    <tr>
                        <th>Admin</th>
                        <td>{{ $rekap_pemasangan->admin }}</td>
                    </tr>
                    <tr>
                        <th>Total Biaya</th>
                        <td>Rp {{ number_format($rekap_pemasangan->total_biaya, 0, ',', '.') }}</td>
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
                <a href="{{ route('rekap_pemasangan.index') }}" class="btn btn-secondary mt-3 no-print">Kembali</a>
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
    </style>

    <script>
        function printPage() {
            window.print();
        }
    </script>
@endsection
