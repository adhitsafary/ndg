@extends($layout)

@section('konten')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <div style="font-weight: 700">Surat Jalan - {{ $perbaikan->nama_plg }}</div>
                <button onclick="printPage()" class="btn btn-light btn-sm no-print">🖨️ Print</button>
            </div>
            <div class="card-body" id="printableArea">
                <!-- HEADER SURAT JALAN -->
                <div class="text-center mb-4">
                    <h3 class="fw-bold">SURAT JALAN</h3>
                    <p class="mb-0">No: {{ $perbaikan->kd_tiket }}</p>
                    <p class="mb-0">Tanggal: {{ \Carbon\Carbon::parse($perbaikan->created_at)->format('d-m-Y') }}</p>
                </div>

                <!-- DETAIL PELANGGAN -->
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Nama Pelanggan</th>
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
                        <th>Gangguan</th>
                        <td>{{ $perbaikan->keterangan }}</td>
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
                </table>





                <h6 class="mt-4">Uji Fungsi Modem</h6>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Indikasi</th>
                            <th>Keadaan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Lampu  Indikator Power Modem ON</td>
                            <td>
                                <input type="checkbox" name="lampu_power" value="Ya"> Ya
                                <input type="checkbox" name="lampu_power" value="Tidak"> Tidak

                            </td>
                        </tr>
                        <tr>
                            <td>Lampu PON Indikator Modem Hijau</td>
                            <td>
                                <input type="checkbox" name="lampu_indikator" value="Ya"> Ya
                                <input type="checkbox" name="lampu_indikator" value="Tidak"> Tidak

                            </td>
                        </tr>
                        <tr>
                            <td>Melakukan Uji Speed Test</td>
                            <td>
                                <input type="checkbox" name="uji_speed" value="Ya"> Ya
                                <input type="checkbox" name="uji_speed" value="Tidak"> Tidak

                            </td>
                        </tr>
                        <tr>
                            <td>Membuka Halaman Website</td>
                            <td>
                                <input type="checkbox" name="buka_web" value="Ya"> Ya
                                <input type="checkbox" name="buka_web" value="Tidak"> Tidak

                            </td>
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
                        <p>Pelanggan</p>
                        <br><br>
                        <p>( ______________________ )</p>
                    </div>
                </div>

                <!-- Tombol Kembali (Tidak Dicetak) -->
                <a href="{{ route('perbaikan.index') }}" class="btn btn-secondary mt-3 no-print">Kembali</a>
            </div>
        </div>
    </div><br><br>

    <!-- CSS untuk Menyembunyikan Elemen Saat Print -->
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                font-size: 14px;
            }

            .table {
                border-collapse: collapse;
                width: 100%;
            }

            .table th,
            .table td {
                border: 1px solid black;
                padding: 5px;
                text-align: left;
            }
        }
    </style>

    <script>
        function printPage() {
            window.print();
        }
    </script>
@endsection
