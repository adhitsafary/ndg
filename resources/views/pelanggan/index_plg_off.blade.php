@extends($layout)

@section('konten')
    <div class="card m-2">
        <!-- Form Filter dan Pencarian -->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-5 mb-3">

            {{-- Total Keseluruhan --}}
            <div class="col mb-4">
                <div class="card shadow-sm border-0 rounded-lg h-100"
                    style="background: linear-gradient(135deg, #0066cc, #54d4ff); color: white;">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-white rounded-circle d-flex align-items-center justify-content-center mr-3"
                            style="width: 50px; height: 50px;">
                            <img src="{{ asset('asset/img/icon/pelanggan2.png') }}" height="30px">
                        </div>
                        <div>
                            <div class="font-weight-semibold mb-1">Total Keseluruhan</div>
                            <div class="h5 mb-0 font-weight-bold">Rp
                                {{ number_format($totalJumlahPembayaranfilter, 0, ',', '.') }}</div>
                            <small><i class="fas fa-users"></i> {{ number_format($totalPelangganfilter, 0, ',', '.') }}
                                user</small>
                        </div>
                    </div>
                </div>
            </div>

           {{-- Bulan 1 --}}
            <div class="col mb-4">
                <a href="{{ route('pelanggan.plg_off', ['bulan' => 1]) }}" class="text-white text-decoration-none">
                    <div class="card shadow-sm border-0 rounded-lg h-100"
                        style="background: linear-gradient(135deg, #28a745, #b8d05f); color: white;">
                        <div class="card-body d-flex align-items-center">
                            <div class="rounded-circle d-flex align-items-center justify-content-center mr-3"
                                style="width: 50px; height: 50px; font-size: 20px; font-weight: bold; color: #fff; background-color: #424242;">
                                1
                            </div>

                            <div>
                                <div class="font-weight-semibold mb-1">Bulan 1</div>
                                <div class="h5 mb-0 font-weight-bold">Rp {{ number_format($totalBulan1, 0, ',', '.') }}
                                </div>
                                <small><i class="fas fa-user-check"></i> {{ $userBulan1 }} user</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Bulan 2 --}}
            <div class="col mb-4">
                <a href="{{ route('pelanggan.plg_off', ['bulan' => 2]) }}" class="text-white text-decoration-none">
                    <div class="card shadow-sm border-0 rounded-lg h-100"
                        style="background: linear-gradient(135deg, #ffc107, #ffea00); color: black;">
                        <div class="card-body d-flex align-items-center">
                            <div class="rounded-circle d-flex align-items-center justify-content-center mr-3"
                                style="width: 50px; height: 50px; font-size: 20px; font-weight: bold; color: #fff; background-color: #424242;">
                                2
                            </div>
                            <div>
                                <div class="font-weight-semibold mb-1">Bulan 2</div>
                                <div class="h5 mb-0 font-weight-bold">Rp {{ number_format($totalBulan2, 0, ',', '.') }}
                                </div>
                                <small><i class="fas fa-user-clock"></i> {{ $userBulan2 }} user</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Bulan 3 --}}
            <div class="col mb-4">
                <a href="{{ route('pelanggan.plg_off', ['bulan' => 3]) }}" class="text-white text-decoration-none">
                    <div class="card shadow-sm border-0 rounded-lg h-100"
                        style="background: linear-gradient(135deg, #dc3545, #f67280); color: white;">
                        <div class="card-body d-flex align-items-center">
                            <div class="rounded-circle d-flex align-items-center justify-content-center mr-3"
                                style="width: 50px; height: 50px; font-size: 20px; font-weight: bold; color: #fff; background-color: #424242;">
                                3
                            </div>
                            <div>
                                <div class="font-weight-semibold mb-1">Bulan 3</div>
                                <div class="h5 mb-0 font-weight-bold">Rp {{ number_format($totalBulan3, 0, ',', '.') }}
                                </div>
                                <small><i class="fas fa-user-times"></i> {{ $userBulan3 }} user</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Bulan 4 --}}
            <div class="col mb-4">
                <a href="{{ route('pelanggan.plg_off', ['bulan' => 4]) }}" class="text-white text-decoration-none">
                    <div class="card shadow-sm border-0 rounded-lg h-100"
                        style="background: linear-gradient(135deg, #6f42c1, #e83e8c); color: white;">
                        <div class="card-body d-flex align-items-center">
                            <div class="rounded-circle d-flex align-items-center justify-content-center mr-3"
                                style="width: 50px; height: 50px; font-size: 20px; font-weight: bold; color: #fff; background-color: #424242;">
                                4
                            </div>
                            <div>
                                <div class="font-weight-semibold mb-1">Bulan 4</div>
                                <div class="h5 mb-0 font-weight-bold">Rp {{ number_format($totalBulan4, 0, ',', '.') }}
                                </div>
                                <small><i class="fas fa-user-lock"></i> {{ $userBulan4 }} user</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>


        <!-- End Form Filter dan pencarian -->

        <div class="d-flex align-items-center justify-content-between mt-2">


            <div class="mx-auto text-center mr-3">
                <h3 class="font-weight-bold"
                    style="
        background: linear-gradient(45deg,rgb(60, 105, 0),rgb(0, 81, 148)); /* Gradasi hijau ke biru */
        -webkit-background-clip: text; /* Clip background pada teks */
        -webkit-text-fill-color: transparent; /* Jadikan teks transparan agar gradasi terlihat */
        font-size: 2em; /* Ukuran font */
        display: inline-block; /* Agar padding sesuai */
    ">
                    Data Isolir
                </h3>
            </div>

            <div class="col-md-3 text-right">
                <div class="btn-group">
                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        Ekspor
                    </button>
                    <div class="dropdown-menu">
                        <a href="{{ route('pelanggan.export_isolir', [
                            'format' => 'pdf',
                            'tgl_tagih_plg' => request('tgl_tagih_plg'),
                            'paket_plg' => request('paket_plg'),
                            'harga_paket' => request('harga_paket'),
                            'status_pembayaran' => request('status_pembayaran'),
                        ]) }}"
                            class="dropdown-item">PDF</a>

                        <a href="{{ route('pelanggan.export_isolir', [
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
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if (session('alert'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('alert') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Tampilkan jumlah total pembayaran dan jumlah pelanggan -->


        <div class="">

            <th class="mt-2">
                <form action="{{ route('pelanggan.plg_off') }}" method="GET">

                    <input type="text" name="search" id="search" class=" font-weight-bold" style="color: black;"
                        value="{{ request('search') }}" placeholder="Pencarian">

                    <select name="tgl_tagih_plg" id="tgl_tagih_plg">
                        <option value="">Tanggal Tagih</option>
                        @for ($i = 1; $i <= 33; $i++)
                            @php
                                $formattedValue = str_pad($i, 2, '0', STR_PAD_LEFT);
                            @endphp
                            <option value="{{ $formattedValue }}"
                                {{ request('tgl_tagih_plg') == $formattedValue ? 'selected' : '' }}>
                                {{ $formattedValue }}
                            </option>
                        @endfor
                        <option value="vcr" {{ request('tgl_tagih_plg') == 'vcr' ? 'selected' : '' }}>
                            vcr
                        </option>
                        <option value=" " {{ request('tgl_tagih_plg') == '0' ? 'selected' : '' }}>
                            0
                        </option>
                    </select>

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

                    <select name="status_pembayaran">
                        <option value="">Semua Status</option>
                        <option value="paid">paid</option>
                        <option value="unpaid">unpaid</option>
                    </select>

                    <select name="bulan_pembayaran">
                        <option value="">Semua Bulan</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}"
                                {{ request('bulan_pembayaran') == $i ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::createFromFormat('m', $i)->locale('id')->isoFormat('MMMM') }}
                            </option>
                        @endfor
                    </select>


                    <input type="date" id="updated_at" name="updated_at" value="{{ request()->get('updated_at') }}">


                    <button type="submit" class="btn btn-primary ">Filter</button>
                </form>
            </th>


            <div class="card">
                <table class="table table-bordered table-responsive"
                    style="color: black; width: 100%; font-size: 0.85em; table-layout: fixed;">
                    <thead style="background-color: rgb(233, 0, 0); color: white; text-align: center;">
                        <tr class="font-weight-bold">
                            <th style="width: 1%; padding: 1px;">No</th>
                            <th style="width: 1%; padding: 1px;">ID</th>
                            <th style="width: 1%; padding: 1px;">Nama</th>
                            <th style="width: 1%; padding: 1px;">Alamat</th>
                            <th style="width: 1%; padding: 1px;">No Telpon</th>
                            <th style="width: 1%; padding: 1px;">Aktivasi</th>
                            <th style="width: 1%; padding: 1px;">Paket</th>
                            <th style="width: 1%; padding: 1px;">Harga</th>
                            <th style="width: 1%; padding: 0; margin: 0; text-align: center;">Tanggal Tagih</th>
                            <th style="width: 1%; padding: 1px;">Keterangan</th>
                            <th style="width: 1%; padding: 1px;">Pembayaran Terakhir</th>
                            <th style="width: 1%; padding: 1px;">Riwayat Pembayaran</th>
                            <th style="width: 1%; padding: 1px;">Off/ON</th>
                            <th style="width: 1%; padding: 1px;">Blok/Non</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pelanggan as $no => $item)
                            <tr>
                                <!-- Nomor -->
                                <td style="padding: 5px; font-size: 0.85em; text-align: center;">
                                    <a href="{{ route('pelanggan.detail', $item->id) }}"
                                        style="text-decoration: none; color: inherit;">
                                        {{ ($pelanggan->currentPage() - 1) * $pelanggan->perPage() + $loop->iteration }}
                                    </a>
                                </td>

                                <!-- ID Pelanggan -->
                                <td style="padding: 5px; font-size: 0.85em; text-align: center;">
                                    <a href="{{ route('pelanggan.detail', $item->id) }}"
                                        style="text-decoration: none; color: inherit;">
                                        {{ $item->id_plg }}
                                    </a>
                                </td>

                                <!-- Nama Pelanggan -->
                                <td style="padding: 5px; font-size: 0.85em; text-align: center;">
                                    <a href="{{ route('pelanggan.detail', $item->id) }}"
                                        style="text-decoration: none; color: inherit;">
                                        {{ $item->nama_plg }}
                                    </a>
                                </td>

                                <!-- Alamat -->
                                <td style="padding: 5px; font-size: 0.85em; text-align: center;">
                                    <a href="{{ route('pelanggan.detail', $item->id) }}"
                                        style="text-decoration: none; color: inherit;">
                                        {{ $item->alamat_plg }}
                                    </a>
                                </td>

                                <!-- No Telepon -->
                                <td style="padding: 5px; font-size: 0.85em; text-align: center;">
                                    <a href="{{ route('pelanggan.detail', $item->id) }}"
                                        style="text-decoration: none; color: inherit;">
                                        {{ $item->no_telepon_plg }}
                                    </a>
                                </td>

                                <!-- Aktivasi -->
                                <td style="padding: 5px; font-size: 0.85em; text-align: center;">
                                    <a href="{{ route('pelanggan.detail', $item->id) }}"
                                        style="text-decoration: none; color: inherit;">
                                        {{ $item->aktivasi_plg }}
                                    </a>
                                </td>

                                <!-- Paket -->
                                <td style="padding: 5px; font-size: 0.85em; text-align: center;">
                                    <a href="{{ route('pelanggan.detail', $item->id) }}"
                                        style="text-decoration: none; color: inherit;">
                                        {{ $item->paket_plg }}
                                    </a>
                                </td>

                                <!-- Harga Paket -->
                                <td style="padding: 5px; font-size: 0.85em; text-align: center;">
                                    <a href="{{ route('pelanggan.detail', $item->id) }}"
                                        style="text-decoration: none; color: inherit;">
                                        {{ number_format($item->harga_paket, 0, ',', '.') }}
                                    </a>
                                </td>

                                <!-- Tanggal Tagihan -->
                                <td style="padding: 5px; font-size: 0.85em; text-align: center;">
                                    <a href="{{ route('pelanggan.detail', $item->id) }}"
                                        style="text-decoration: none; color: inherit;">
                                        {{ $item->tgl_tagih_plg }}
                                    </a>
                                </td>

                                <!-- Keterangan -->
                                <td style="padding: 5px; font-size: 0.85em; text-align: center;">
                                    <a href="{{ route('pelanggan.detail', $item->id) }}"
                                        style="text-decoration: none; color: inherit;">
                                        {{ $item->keterangan_plg }}
                                    </a>
                                </td>

                                <!-- Bayar Terakhir -->
                                <td style="padding: 5px; font-size: 0.85em; text-align: center;">
                                    <a href="{{ route('pelanggan.detail', $item->id) }}"
                                        style="text-decoration: none; color: inherit;">
                                        {{ optional($item->pembayaranTerakhir)->tanggal_pembayaran
                                            ? \Carbon\Carbon::parse($item->pembayaranTerakhir->tanggal_pembayaran)->locale('id')->isoFormat('MMMM Y')
                                            : '-' }}
                                    </a>
                                </td>

                                <!-- Riwayat Pembayaran -->
                                <td style="padding: 5px; font-size: 0.85em; text-align: center;">
                                    <select name="tanggal_pembayaran" class="form-control"
                                        style="width: 100%; padding: 2px; height: 25px;" onchange="this.form.submit()">
                                        <option value="">Riwayat Pembayaran</option>
                                        @foreach ($item->pembayaran as $pembayaran)
                                            @php
                                                $tanggalPembayaran = \Carbon\Carbon::parse(
                                                    $pembayaran->tanggal_pembayaran,
                                                );
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
                                        class="badge {{ strcasecmp($item->status_pembayaran, 'paid') === 0 ? 'bg-success' : 'bg-danger' }} text-white"
                                        style="font-size: 0.75em; padding: 2px;">{{ $item->status_pembayaran }}</span>
                                </td>

                                <td>
                                    <a href="{{ route('pelanggan.ubahStatusOff', $item->id) }}"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Apakah {{ $item->nama_plg }} Akan di Non Aktifkan?')">Off</a>
                                </td>

                                <!-- Tombol Status -->
                                <td style="padding: 5px; font-size: 0.85em; text-align: center;">
                                    @if (auth()->user()->role === 'superadmin')
                                        <form action="{{ route('pelanggan.toggleStatus', $item->id) }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="status"
                                                value="{{ $item->status_pembayaran === 'Block' ? 'nonblock' : 'block' }}">
                                            <button type="submit"
                                                style="border: none; background: none; cursor: pointer;">
                                                <img src="{{ asset('asset/img/' . ($item->status_pembayaran === 'Block' ? 'off.png' : 'on.png')) }}"
                                                    alt="{{ $item->status_pembayaran === 'Block' ? 'Nonblock' : 'Block' }}"
                                                    style="width: 30px; height: auto;">
                                            </button>
                                        </form>
                                    @else
                                        <button style="border: none; background: none; cursor: not-allowed;" disabled>
                                            <img src="{{ asset('asset/img/' . ($item->status_pembayaran === 'Block' ? 'off.png' : 'on.png')) }}"
                                                alt="Role restricted" style="width: 30px; height: auto;">
                                        </button>
                                    @endif
                                </td>





                            </tr>

                        @empty
                            <tr>
                                <td colspan="19" class="text-center">Tidak ada data ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>


        </div>
        <div class="d-flex justify-content-center">
            {{ $pelanggan->links('pagination::bootstrap-4') }}
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
