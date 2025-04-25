@extends($layout)

@section('konten')
    <div class=" card pl-5 pr-5 m-5">
        <!-- Form Filter dan Pencarian -->
        <div class="row align-items-center">
            <table class="table table-bordered mt-2">
                <thead class="custom-cell head">
                    <tr>
                        <!-- <th>Total Pencarian</th> -->
                        <th>Total Keseluruhan</th>
                        <th>Total Paid</th>
                        <th>Total Sisa</th>
                        <th>Total Unpaid</th>
                        <th>Total Isolir</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>

                        <td class="custom-cell primary">
                            Rp {{ number_format($totalJumlahPembayaranfilter, 0, ',', '.') }} User:
                            {{ number_format($totalPelangganfilter, 0, ',', '.') }}
                        </td>


                        <td class="custom-cell primary">
                            Rp {{ number_format($totalPembayaranSudahBayar, 0, ',', '.') }} User: {{ $totalSudahBayar }}
                        </td>

                        <td class="custom-cell primary">
                            Rp {{ number_format($totalSisa_Uang, 0, ',', '.') }} User: {{ $totalSisa_User }}
                        </td>

                        <td class="custom-cell warning">
                            Rp {{ number_format($totalPembayaranBelumBayar, 0, ',', '.') }} User: {{ $totalBelumBayar }}
                        </td>

                        <td class="custom-cell danger">
                            <a href="{{ route('pelanggan.isolir') }}"> Rp
                                {{ number_format($totalPembayaranIsolir, 0, ',', '.') }} User: {{ $totalIsolir }}</a>
                        </td>

                    </tr>
                </tbody>
            </table>

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

        </div>

        <!-- End Form Filter dan pencarian -->

        <div class="d-flex align-items-center justify-content-between mt-2">
            <!--   <form action="{{ route('pelanggan.index') }}" method="GET" class="form-inline d-flex" style="color: black;">
                                                                                                                                                                                        <div class="input-group" style="color: black;">
                                                                                                                                                                                            <input type="text" name="search" id="search" class="form-control font-weight-bold"
                                                                                                                                                                                                style="color: black;" value="{{ request('search') }}" placeholder="Pencarian">
                                                                                                                                                                                        </div>
                                                                                                                                                                                        <button type="submit" name="action" value="search" class="btn btn-danger ml-2">Cari</button>
                                                                                                                                                                                    </form> -->


            <div class="mx-auto text-center mr-3">
                <h3 class="font-weight-bold"
                    style="
        background: linear-gradie   nt(45deg,rgb(60, 105, 0),rgb(0, 81, 148)); /* Gradasi hijau ke biru */
        -webkit-background-clip: text; /* Clip background pada teks */
        -webkit-text-fill-color: transparent; /* Jadikan teks transparan agar gradasi terlihat */
        font-size: 2em; /* Ukuran font */
        display: inline-block; /* Agar padding sesuai */
    ">
                    Data Pelanggan
                </h3>
            </div>

            <div class="col-md-3 text-right">
                <div class="btn-group">
                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        Ekspor
                    </button>
                    <div class="dropdown-menu">
                        <a href="{{ route('pelanggan.export', [
                            'format' => 'pdf',
                            'tgl_tagih_plg' => request('tgl_tagih_plg'),
                            'paket_plg' => request('paket_plg'),
                            'harga_paket' => request('harga_paket'),
                            'status_pembayaran' => request('status_pembayaran'),
                        ]) }}"
                            class="dropdown-item">PDF</a>

                        <a href="{{ route('pelanggan.export', [
                            'format' => 'excel',
                            'tgl_tagih_plg' => request('tgl_tagih_plg'),
                            'paket_plg' => request('paket_plg'),
                            'harga_paket' => request('harga_paket'),
                            'status_pembayaran' => request('status_pembayaran'),
                        ]) }}"
                            class="dropdown-item">Excel</a>
                    </div>

                </div>
            </div>

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

        <!-- Tampilkan jumlah total pembayaran dan jumlah pelanggan -->


        <div class="">

            <th class="mt-2">
                <form action="{{ route('pelanggan.index') }}" method="GET">

                    <input type="text" name="search" id="search" class=" font-weight-bold" style="color: black;"
                        value="{{ request('search') }}" placeholder="Pencarian">

                    <style>
                        .dropdown-container {
                            position: relative;
                            display: inline-block;
                        }

                        .dropdown-checkbox {
                            display: none;
                            position: absolute;
                            background-color: white;
                            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
                            max-height: 200px;
                            overflow-y: auto;
                            width: 100%;
                            border: 1px solid #ccc;
                            padding: 10px;
                            z-index: 1000;
                        }

                        .dropdown-container:hover .dropdown-checkbox {
                            display: block;
                        }

                        .dropdown-button {
                            text-align: center;

                            width: 100px;
                            height: 25px;
                            border: 1px solid #000000;
                            background-color: white;

                        }
                    </style>

                    <div class="dropdown-container">
                        <input type="text" id="tgl_tagih_input" placeholder="Pilih Tanggal" readonly>

                        <div class="dropdown-checkbox">
                            @for ($i = 1; $i <= 33; $i++)
                                @php
                                    $formattedValue = str_pad($i, 2, '0', STR_PAD_LEFT);
                                @endphp
                                <label>
                                    <input type="checkbox" name="tgl_tagih_plg[]" value="{{ $formattedValue }}"
                                        {{ is_array(request('tgl_tagih_plg')) && in_array($formattedValue, request('tgl_tagih_plg')) ? 'checked' : '' }}
                                        onchange="updateInput()">
                                    {{ $formattedValue }}
                                </label>
                                <br>
                            @endfor

                            <label>
                                <input type="checkbox" name="tgl_tagih_plg[]" value="vcr"
                                    {{ is_array(request('tgl_tagih_plg')) && in_array('vcr', request('tgl_tagih_plg')) ? 'checked' : '' }}
                                    onchange="updateInput()">
                                VCR
                            </label>
                            <br>

                            <label>
                                <input type="checkbox" name="tgl_tagih_plg[]" value="0"
                                    {{ is_array(request('tgl_tagih_plg')) && in_array('0', request('tgl_tagih_plg')) ? 'checked' : '' }}
                                    onchange="updateInput()">
                                0
                            </label>
                        </div>
                    </div>

                    <script>
                        function updateInput() {
                            let checkboxes = document.querySelectorAll('input[name="tgl_tagih_plg[]"]:checked');
                            let selectedValues = Array.from(checkboxes).map(cb => cb.value);
                            document.getElementById('tgl_tagih_input').value = selectedValues.join(', ');
                        }
                    </script>


                    <div class="dropdown-container">
                        <input type="text" id="paket_plg_input" placeholder="Pilih Paket" readonly>

                        <div class="dropdown-checkbox">
                            @for ($i = 1; $i <= 7; $i++)
                                <label>
                                    <input type="checkbox" name="paket_plg[]" value="{{ $i }}"
                                        {{ is_array(request('paket_plg')) && in_array($i, request('paket_plg')) ? 'checked' : '' }}
                                        onchange="updateInput()">
                                    Paket {{ $i }}
                                </label>
                                <br>
                            @endfor

                            <label>
                                <input type="checkbox" name="paket_plg[]" value="vcr"
                                    {{ is_array(request('paket_plg')) && in_array('vcr', request('paket_plg')) ? 'checked' : '' }}
                                    onchange="updateInput()">
                                VCR
                            </label>
                        </div>
                    </div>

                    <script>
                        function updateInput() {
                            let checkboxes = document.querySelectorAll('input[name="paket_plg[]"]:checked');
                            let selectedValues = Array.from(checkboxes).map(cb => cb.value);
                            document.getElementById('paket_plg_input').value = selectedValues.join(', ');
                        }
                    </script>


                    <div class="dropdown-container">
                        <input type="text" id="harga_paket_input" placeholder="Pilih Harga" readonly>

                        <div class="dropdown-checkbox">
                            @php
                                $hargaList = [
                                    50000,
                                    75000,
                                    100000,
                                    105000,
                                    115000,
                                    120000,
                                    125000,
                                    150000,
                                    165000,
                                    175000,
                                    205000,
                                    250000,
                                    265000,
                                    305000,
                                    750000,
                                ];
                            @endphp

                            @foreach ($hargaList as $harga)
                                <label>
                                    <input type="checkbox" name="harga_paket[]" value="{{ $harga }}"
                                        {{ is_array(request('harga_paket')) && in_array($harga, request('harga_paket')) ? 'checked' : '' }}
                                        onchange="updateHargaInput()">
                                    {{ number_format($harga, 0, ',', '.') }}
                                </label>
                                <br>
                            @endforeach

                            <label>
                                <input type="checkbox" name="harga_paket[]" value="vcr"
                                    {{ is_array(request('harga_paket')) && in_array('vcr', request('harga_paket')) ? 'checked' : '' }}
                                    onchange="updateHargaInput()">
                                VCR
                            </label>
                        </div>
                    </div>

                    <script>
                        function updateHargaInput() {
                            let checkboxes = document.querySelectorAll('input[name="harga_paket[]"]:checked');
                            let selectedValues = Array.from(checkboxes).map(cb => cb.value);
                            document.getElementById('harga_paket_input').value = selectedValues.join(', ');
                        }
                    </script>


                    <div class="dropdown-container">
                        <input type="text" id="status_pembayaran_input" placeholder="Pilih Status" readonly
                            onclick="toggleDropdown()">

                        <div class="dropdown-checkbox" id="statusDropdown" style="display: none;">
                            @php
                                $statusList = ['paid', 'unpaid', 'Isolir'];
                                $selectedStatus = request()->has('status_pembayaran')
                                    ? explode(',', request('status_pembayaran'))
                                    : [];
                            @endphp

                            @foreach ($statusList as $status)
                                <label>
                                    <input type="checkbox" class="status-checkbox" value="{{ $status }}"
                                        {{ in_array($status, $selectedStatus) ? 'checked' : '' }}
                                        onchange="updateStatusInput()">
                                    {{ ucfirst($status) }}
                                </label>
                                <br>
                            @endforeach
                        </div>
                    </div>

                    <!-- Input hidden untuk Laravel -->
                    <input type="hidden" name="status_pembayaran" id="status_pembayaran_hidden">

                    <script>
                        function updateStatusInput() {
                            let checkboxes = document.querySelectorAll('.status-checkbox:checked');
                            let selectedValues = Array.from(checkboxes).map(cb => cb.value);

                            document.getElementById('status_pembayaran_input').value = selectedValues.join(', ');
                            document.getElementById('status_pembayaran_hidden').value = selectedValues.join(
                                '&status_pembayaran='); // Format tanpa []
                        }

                        function toggleDropdown() {
                            let dropdown = document.getElementById('statusDropdown');
                            dropdown.style.display = dropdown.style.display === "none" ? "block" : "none";
                        }

                        document.addEventListener('click', function(event) {
                            let dropdown = document.getElementById('statusDropdown');
                            let input = document.getElementById('status_pembayaran_input');

                            if (!input.contains(event.target) && !dropdown.contains(event.target)) {
                                dropdown.style.display = "none";
                            }
                        });
                    </script>

                    <select name="bulan_pembayaran" id="bulan_pembayaran">
                        <option value="">Semua Bulan</option>
                        @for ($i = 1; $i <= 12; $i++)
                            @php
                                $bulanValue = str_pad($i, 2, '0', STR_PAD_LEFT); // Format 01-12
                                $bulanRequest = request('bulan_pembayaran');
                            @endphp
                            <option value="{{ $bulanValue }}" {{ $bulanRequest == $bulanValue ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::createFromFormat('m', $bulanValue)->locale('id')->isoFormat('MMMM') }}
                            </option>
                        @endfor
                    </select>


                    <!-- <input type="date" id="updated_at" name="updated_at" value="{{ request()->get('updated_at') }}"> -->


                    <button type="submit" class="btn btn-primary ">Filter</button>
                </form>
            </th>


            <div class="card ">
                <table class="table table-bordered table-responsive"
                    style="color: black; width: 100%; font-size: 0.85em; table-layout: fixed;">
                    <thead class="custom-cell danger" style="color: white;">
                        <tr class="font-weight-bold">
                            <th style="width: 1%; padding: 1px;">No</th>
                            <th style="width: 1%; padding: 1px;">ID</th>
                            <th style="width: 1%; padding: 1px;">Nama</th>
                            <th style="width: 1%; padding: 1px;">Alamat</th>
                            <th style="width: 1%; padding: 1px;">ODP</th>
                            <th style="width: 1%; padding: 1px;">No Telpon</th>
                            <th style="width: 1%; padding: 1px;">Aktivasi</th>
                            <th style="width: 1%; padding: 1px;">Paket</th>
                            <th style="width: 1%; padding: 1px;">Harga</th>
                            <th style="width: 1%; padding: 0; margin: 0; text-align: center;">Tanggal Tagih</th>
                            <th style="width: 1%; padding: 0; margin: 0; text-align: center;">Kategori</th>
                            <th style="width: 1%; padding: 0; margin: 0; text-align: center;">Tanggungan</th>
                            <th style="width: 1%; padding: 1px;">Keterangan</th>
                            <th style="width: 1%; padding: 1px;">Bayar Terakhir</th>
                            <th style="width: 1%; padding: 1px;">Status Pembayaran</th>
                            <th style="width: 1%; padding: 1px;">OFF/ON</th>

                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pelanggan as $no => $item)
                            <tr class="">
                                <td style="padding: 1px;">
                                    <a href="{{ route('pelanggan.detail', $item->id) }}"
                                        style="text-decoration: none; color: inherit;">
                                        {{ ($pelanggan->currentPage() - 1) * $pelanggan->perPage() + $loop->iteration }}
                                    </a>
                                </td>

                                <!-- ID Pelanggan -->
                                <td style="padding: 1px;">
                                    <a href="{{ route('pelanggan.detail', $item->id) }}"
                                        style="text-decoration: none; color: inherit;">
                                        {{ $item->id_plg }}
                                    </a>
                                </td>

                                <!-- Nama Pelanggan -->
                                <td style="padding: 1px;">
                                    <a href="{{ route('pelanggan.detail', $item->id) }}"
                                        style="text-decoration: none; color: inherit;">
                                        {{ $item->nama_plg }}
                                    </a>
                                </td>

                                <td style="padding: 1px;">
                                    <a href="{{ route('pelanggan.detail', $item->id) }}"
                                        style="text-decoration: none; color: inherit;">
                                        {{ $item->alamat_plg }}
                                    </a>
                                </td>
                                <td style="padding: 1px;">
                                    @if (!empty($item->odp))
                                        <!-- Jika ODP sudah ada, tampilkan nilainya saja -->
                                        <span>{{ $item->odp }}</span>
                                    @else
                                        <!-- Jika ODP kosong, tampilkan tombol Edit -->
                                        <button class="btn btn-primary btn-sm"
                                            onclick="showUpdateOdpModal('{{ $item->id }}', '{{ $item->odp }}')">
                                            Edit ODP
                                        </button>
                                    @endif
                                </td>



                                <td style="padding: 1px;">
                                    <a href="{{ route('pelanggan.detail', $item->id) }}"
                                        style="text-decoration: none; color: inherit;">
                                        {{ $item->no_telepon_plg }}
                                    </a>
                                </td>
                                <td style="padding: 1px;">
                                    <a href="{{ route('pelanggan.detail', $item->id) }}"
                                        style="text-decoration: none; color: inherit;">
                                        {{ $item->aktivasi_plg }}
                                    </a>
                                </td>
                                <td style="padding: 1px;">
                                    <a href="{{ route('pelanggan.detail', $item->id) }}"
                                        style="text-decoration: none; color: inherit;">
                                        {{ $item->paket_plg }}
                                    </a>
                                </td>
                                <td style="padding: 1px;">
                                    <a href="{{ route('pelanggan.detail', $item->id) }}"
                                        style="text-decoration: none; color: inherit;">
                                        {{ number_format($item->harga_paket, 0, ',', '.') }}
                                    </a>
                                </td>
                                <td style="width: 1%; padding: 0; margin: 0; text-align: center;">
                                    <a href="{{ route('pelanggan.detail', $item->id) }}"
                                        style="text-decoration: none; color: inherit;">
                                        {{ $item->tgl_tagih_plg }}
                                    </a>
                                </td>
                                <td style="width: 1%; padding: 0; margin: 0; text-align: center;">
                                    <a href="{{ route('pelanggan.detail', $item->id) }}"
                                        style="text-decoration: none; color: inherit;">
                                        {{ $item->kt_plg }}
                                    </a>
                                </td>
                                @php
                                    $totalTagihan = null;

                                    try {
                                        // Coba format pertama: Y-m-d
                                        $created = \Carbon\Carbon::parse($item->aktivasi_plg);
                                    } catch (\Exception $e1) {
                                        try {
                                            // Coba format kedua: d/m/Y
                                            $created = \Carbon\Carbon::createFromFormat('d/m/Y', $item->aktivasi_plg);
                                        } catch (\Exception $e2) {
                                            $created = null;
                                        }
                                    }

                                    if ($created) {
                                        $now = \Carbon\Carbon::now();
                                        $selisihBulan = $created->diffInMonths($now);

                                        $totalTagihan = $selisihBulan > 6 ? 0 : $selisihBulan * $item->harga_paket;
                                    }
                                @endphp

                                <td style="width: 1%; padding: 0; margin: 0; text-align: center;">
                                    <a href="{{ route('pelanggan.detail', $item->id) }}"
                                        style="text-decoration: none; color: inherit;">
                                        @if (is_null($totalTagihan))
                                            Data tidak ada
                                        @else
                                            {{ number_format($totalTagihan, 0, ',', '.') }}
                                        @endif
                                    </a>
                                </td>

                                <td style="padding: 1px;">
                                    <a href="{{ route('pelanggan.detail', $item->id) }}"
                                        style="text-decoration: none; color: inherit;">
                                        {{ $item->keterangan_plg }}
                                    </a>
                                </td>

                                <td style="padding: 1px;">
                                    <a href="{{ route('pelanggan.detail', $item->id) }}"
                                        style="text-decoration: none; color: inherit;">
                                        {{ optional($item->pembayaranTerakhir)->tanggal_pembayaran
                                            ? \Carbon\Carbon::parse($item->pembayaranTerakhir->tanggal_pembayaran)->locale('id')->isoFormat('MMMM Y')
                                            : '-' }}
                                    </a>
                                </td>

                                <!---
                                                                                                                                                                                                                                                                <td style="padding: 1px;">
                                                                                                                                                                                                                                                                    <span class="badge {{ strcasecmp($item->status_pembayaran, 'paid') === 0 ? 'bg-success' : 'bg-danger' }} text-white">
                                                                                                                                                                                                                                                                        {{ $item->status_pembayaran }}
                                                                                                                                                                                                                                                                    </span>
                                                                                                                                                                                                                                                                </td>
                                                                                                                                                                                                                                                                    -->
                                <td class="row" style="padding: 2px; font-size: 0.8em; height: 10px;">

                                    <select name="tanggal_pembayaran" class="form-control ml-4"
                                        onchange="this.form.submit()" style="width: 16%;  padding: 2px; height: 25px;">
                                        <option value="">Riwayat Pembayaran</option>
                                        @foreach ($item->pembayaran as $pembayaran)
                                            @php
                                                $tanggalPembayaran = \Carbon\Carbon::parse(
                                                    $pembayaran->tanggal_pembayaran,
                                                );
                                                $isDanger = $tanggalPembayaran->lessThan(now()->startOfMonth());
                                            @endphp
                                            <option value="{{ $tanggalPembayaran->format('Y-m-d') }}">
                                                {{ $tanggalPembayaran->locale('id')->isoFormat('MMMM Y') }}
                                            </option>
                                        @endforeach
                                        @if (!$item->pembayaran->count())
                                            <option value="">Belum Ada Pembayaran</option>
                                        @endif
                                    </select>
                                    <span
                                        class="badge {{ strcasecmp($item->status_pembayaran, 'paid') === 0 ? 'bg-success' : 'bg-danger' }} text-white ml-2"
                                        style="font-size: 0.75em; height: 20px; line-height: 20px;">{{ $item->status_pembayaran }}</span>
                                </td>


                                <td>
                                    <a href="{{ route('pelanggan.off', $item->id) }}" class="btn btn-danger btn-sm"
                                        style="font-size: 12px; padding: 4px 8px;"
                                        onclick="return confirm('Apakah {{ $item->nama_plg }} Akan di Non Aktifkan?')">Off</a>
                                </td>





                                <!--  <td style="padding: 0; margin: 0; text-align: center;">
                                                                                                                                                                                                                                                        <a href="{{ route('pelanggan.detail', $item->id) }}" class="btn btn-warning btn-xs" style="padding: 2px 5px; font-size: 0.75em;">Detail</a>
                                                                                                                                                                                                                                                    </td> -->

                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" class="text-center" style="padding: 10px;">Tidak ada data ditemukan
                                </td>
                            </tr>
                        @endforelse



                    </tbody>
                </table>
            </div>


        </div>
        <div class="d-flex justify-content-center">
            {{ $pelanggan->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>

        <div class="modal fade" id="updateOdpModal" tabindex="-1" role="dialog" aria-labelledby="updateOdpModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="POST" action="" id="updateOdpForm">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="updateOdpModalLabel">Update ODP</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" id="updateOdpIdPlg">
                            <div class="form-group">
                                <label for="odp">ODP</label>
                                <input type="text" class="form-control" name="odp" id="updateOdpInput" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection
    <script>
        function showBayarModal(id, namaPlg, hargaPaket) {
            document.getElementById('pelangganId').value = id;
            document.getElementById('pembayaranDetails').innerText =
                `Nama Pelanggan: ${namaPlg}\nHarga Paket: Rp. ${hargaPaket}`;

            var form = document.getElementById('bayarForm');
            form.action = `/pelanggan/${id}/bayar`; // Set action URL with the ID

            var bayarModal = new bootstrap.Modal(document.getElementById('bayarModal'));
            bayarModal.show();
        }
    </script>

    <script>
        function showUpdateOdpModal(idPlg, odp) {
            // Isi data modal
            document.getElementById('updateOdpIdPlg').value = idPlg;
            document.getElementById('updateOdpInput').value = odp;

            // Set action form
            document.getElementById('updateOdpForm').action = `/update-odp/${idPlg}`;

            // Tampilkan modal
            const modal = new bootstrap.Modal(document.getElementById('updateOdpModal'));
            modal.show();
        }
    </script>
