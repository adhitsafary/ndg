@extends($layout)

@section('konten')
    <div class=" m-5"> <br><br>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="/rekap_pemasangan/create" class="btn btn-success btn-sm">➕ Buat Rekap pemasangan</a>
        </div>
        <div class=" card pl-5 pr-5 mb-4">
            <!-- Form Filter dan Pencarian -->
            <h2 style="color: black;" class="text-center font font-weight-bold">Data Rekap pemasangan</h2> <br>

            <div class="row align-items-center ">

                <table class="table table-bordered ">
                    <thead class="custom-cell head">
                        <tr>
                            <th>Total Biaya PSB Bulanan</th>
                            <th>Total Registrasi PSB Bulanan</th>
                            <th>Total Paket PSB Bulanan</th>
                            <th>Total Inventory Digunakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="custom-cell primary-yellow">
                                Rp {{ number_format($totalBiaya, 0, ',', '.') }} User:
                                {{ number_format($totalUser_bulanan, 0, ',', '.') }}
                            </td>
                            <td class="custom-cell info">
                                Rp {{ number_format($totalHarga_aktivasi, 0, ',', '.') }} User: {{ $totalUser_aktivasi }}
                            </td>
                            <td class="custom-cell primary">
                                Rp {{ number_format($totalPaket_Bulanan, 0, ',', '.') }} User: {{ $totalUserPaket_bulanan }}
                            </td>
                            <td class="custom-cell primary">
                                Rp {{ number_format($totalBiaya_Inventory, 0, ',', '.') }} Untuk User:
                                {{ $totalUser_Inventory }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <br><br>
            <th class="mt-5">
                <form action="{{ route('rekap_pemasangan.index') }}" method="GET">

                    <input type="text" name="search" id="search" class=" font-weight-bold" style="color: black;"
                        value="{{ request('search') }}" placeholder="Pencarian">

                    <select name="paket_plg" id="paket_plg">
                        <option value="">Paket</option>
                        @for ($i = 1; $i <= 7; $i++)
                            <option value="{{ $i }}" {{ request('paket_plg') == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                        <option value="vcr" {{ request('paket_plg') == 'vcr' ? 'selected' : '' }}>
                            vcr
                        </option>
                    </select>

                    <select name="harga_paket" id="harga_paket">
                        <option value="">Harga</option>
                        <option value="50000" {{ request('jumlah_pembayaran') == '50000' ? 'selected' : '' }}>
                            {{ number_format(50000, 0, ',', '.') }}
                        </option>
                        <option value="75000" {{ request('jumlah_pembayaran') == '75000' ? 'selected' : '' }}>
                            {{ number_format(75000, 0, ',', '.') }}
                        </option>
                        <option value="100000" {{ request('jumlah_pembayaran') == '100000' ? 'selected' : '' }}>
                            {{ number_format(100000, 0, ',', '.') }}
                        </option>
                        <option value="105000" {{ request('jumlah_pembayaran') == '105000' ? 'selected' : '' }}>
                            {{ number_format(105000, 0, ',', '.') }}
                        </option>
                        <option value="115000" {{ request('jumlah_pembayaran') == '115000' ? 'selected' : '' }}>
                            {{ number_format(115000, 0, ',', '.') }}
                        </option>

                        <option value="120000" {{ request('jumlah_pembayaran') == '120000' ? 'selected' : '' }}>
                            {{ number_format(120000, 0, ',', '.') }}
                        </option>
                        <option value="125000" {{ request('jumlah_pembayaran') == '125000' ? 'selected' : '' }}>
                            {{ number_format(125000, 0, ',', '.') }}
                        </option>
                        <option value="150000" {{ request('jumlah_pembayaran') == '150000' ? 'selected' : '' }}>
                            {{ number_format(150000, 0, ',', '.') }}
                        </option>
                        <option value="165000" {{ request('jumlah_pembayaran') == '165000' ? 'selected' : '' }}>
                            {{ number_format(165000, 0, ',', '.') }}
                        </option>
                        <option value="175000" {{ request('jumlah_pembayaran') == '175000' ? 'selected' : '' }}>
                            {{ number_format(175000, 0, ',', '.') }}
                        </option>
                        <option value="205000" {{ request('jumlah_pembayaran') == '205000' ? 'selected' : '' }}>
                            {{ number_format(205000, 0, ',', '.') }}
                        </option>
                        <option value="250000" {{ request('jumlah_pembayaran') == '250000' ? 'selected' : '' }}>
                            {{ number_format(250000, 0, ',', '.') }}
                        </option>
                        <option value="265000" {{ request('jumlah_pembayaran') == '265000' ? 'selected' : '' }}>
                            {{ number_format(265000, 0, ',', '.') }}
                        </option>
                        <option value="305000" {{ request('jumlah_pembayaran') == '305000' ? 'selected' : '' }}>
                            {{ number_format(305000, 0, ',', '.') }}
                        </option>
                        <option value="750000" {{ request('jumlah_pembayaran') == '750000' ? 'selected' : '' }}>
                            {{ number_format(750000, 0, ',', '.') }}
                        </option>
                        <option value="vcr" {{ request('jumlah_pembayaran') == 'vcr' ? 'selected' : '' }}>
                            vcr
                        </option>
                    </select>

                    <input type="date" name="created_at_dari"
                        id="created_at_dari" "
                                                                                                                                            value="{{ request('created_at_dari') }}" placeholder="Dari Tanggal">

                                                                                                                                        <input type="date" name="created_at_sampai" id="created_at_sampai" "
                        value="{{ request('created_at_sampai') }}" placeholder="Sampai Tanggal">

                    <button type="submit" class="btn btn-primary ">Filter</button>
                </form>
            </th> <br>

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

            <!-- Modal Notifikasi -->
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
                            <h3 class="text-success"></h3> <!-- Ikon besar -->
                            <p id="successMessage" class="mt-2"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>

            <div style="overflow-x: auto; max-width: 100%;">
                <table class="table table-bordered table-responsive"
                    style="color: black; font-size: 10px; table-layout: fixed; width: 100%;">
                    <thead class="table table-primary" style="color: black;">
                        <tr>
                            <th style="width: 30px; white-space: nowrap;">No</th>
                            <th style="width: 60px; white-space: nowrap;">ID Pelanggan</th>
                            <th style="width: 80px; white-space: nowrap;">Identitas</th>
                            <th style="width: 100px; white-space: nowrap;">Nama</th>
                            <th style="width: 150px; white-space: nowrap;">Alamat</th>
                            <th style="width: 90px; white-space: nowrap;">No Telepon</th>
                            <th style="width: 90px; white-space: nowrap;">Tgl Aktivasi</th>
                            <th style="width: 80px; white-space: nowrap;">Registrasi</th>
                            <th style="width: 80px; white-space: nowrap;">Marketing</th>
                            <th style="width: 70px; white-space: nowrap;">Cabang</th>
                            <th style="width: 70px; white-space: nowrap;">Status</th>
                            <th style="width: 90px; white-space: nowrap;">Aktivasi</th>
                            <th style="width: 10cm;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rekap_pemasangan  as $no => $item)
                            <tr class="font-weight-bold">
                                <td>{{ $no + 1 }}</td>
                                <td>{{ $item->id_plg }}</td>
                                <td>{{ $item->nik }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->alamat }}</td>
                                <td>{{ $item->no_telpon }}</td>
                                <td>{{ $item->tgl_aktivasi }}</td>
                                <td>{{ $item->registrasi }}</td>
                                <td>{{ $item->marketing }}</td>
                                <td>{{ $item->cabang }}</td>
                                <td>{{ $item->status }}</td>

                                <td>
                                    @php
                                        // Cek apakah pelanggan sudah diaktivasi
                                        $isActivated = \App\Models\Pelanggan::where('id_plg', $item->id_plg)->exists();
                                    @endphp
                                    @if ($isActivated)
                                        <!-- Jika sudah diaktivasi, tampilkan ikon ceklis di atas -->
                                        <img src="{{ asset('asset/img/ceklis2.png') }}" alt="Sudah Aktivasi"
                                            style="width:45px; height:45px; display: block; margin-bottom: 10px;">
                                    @else
                                        <!-- Jika belum diaktivasi, tampilkan gambar X dan tombol aktivasi di bawahnya -->
                                        <a href="{{ route('rekap_pemasangan.aktivasi', $item->id) }}"
                                            onclick="return confirm('Apakah Pelanggan Sudah Selesai Pasang?')">
                                            <img src="{{ asset('asset/img/x.png') }}" alt="Belum Aktivasi"
                                                style="width:40px; height:40px;">
                                        </a>
                                    @endif

                                </td>

                                <td>
                                    <a href="{{ route('psb.show', $item->id) }}" class="btn btn-info btn-sm">Detail</a>
                                    <a href="{{ route('inventory.returnForm_psb', $item->id) }}"
                                        class="btn btn-info btn-sm">Return</a>
                                    <a href="{{ route('rekap_pemasangan.edit', $item->id) }}"
                                        class="btn btn-warning btn-sm">Edit</a>

                                    <form action="{{ route('rekap_pemasangan.destroy', $item->id) }}" method="POST"
                                        class="d-inline-block">
                                        @csrf
                                        <button class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
                                    </form>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="text-center">Tidak ada data ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3 mb-5">
                {{ $rekap_pemasangan->links('pagination::bootstrap-4') }}
            </div>
        </div>


        <script>
            document.addEventListener("DOMContentLoaded", function() {
                @if (session('success'))
                    // Isi pesan modal dengan session success
                    document.getElementById("successMessage").innerText = "✅ {{ session('success') }}";

                    // Tampilkan modal
                    var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                    successModal.show();
                @endif
            });
        </script>


        <style>
            .custom-cell {
                padding: 10px;
                text-align: center;
                font-size: 1.0em;
                font-weight: bold;
                cursor: pointer;
                color: white;
            }

            .custom-cell.head {
                background: #530096;
                /* Biru */
            }

            .custom-cell.info {
                background: #17a2b8;
                /* Biru */
            }

            .custom-cell.warning {
                background: #ffc107;
                /* Kuning */
                color: black;
            }

            .custom-cell.danger {
                background: #dc3545;
                /* Merah */
            }

            .custom-cell.success {
                background: #28a745;
                /* Hijau */
            }

            .custom-cell.primary {
                background: #007bff;
                /* Biru tua */
            }

            .custom-cell.primary-yellow {
                background: #ecc100;
                /* Kuning terang */
                color: black;
            }

            .custom-cell.primary-red {
                background: #ff0000;
                /* Merah terang */
            }

            .custom-cell.primary-green {
                background: rgb(32, 190, 0);
                /* Hijau terang */
            }

            .table-bordered {
                border: 1px solid #dee2e6;
                width: 100%;
            }

            .table th,
            .table td {
                border: 1px solid #dee2e6;
                vertical-align: middle;
            }

            .table {
                width: 100%;
                table-layout: fixed;
                /* Membuat lebar kolom rata */
            }

            a {
                color: white;
                text-decoration: none;
            }

            a:hover {
                text-decoration: underline;
            }
        </style>
    @endsection
