@extends($layout)

@section('konten')
    <div class="container-fluid" id="container-wrapper">

        <div class="row mb-3">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card shadow-sm border-0 rounded-lg"
                    style="background: linear-gradient(135deg, #0066cc, #54d4ff); color: white;">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-white rounded-circle d-flex align-items-center justify-content-center mr-3"
                            style="width: 50px; height: 50px;">
                            <img src="{{ asset('asset/img/icon/pelanggan2.png') }}" height="30px">
                        </div>
                        <div>
                            <div class="font-weight-semibold mb-1">Total Tagihan</div>
                            <div class="h5 mb-0 font-weight-bold">Rp
                                {{ number_format($total_jml_pembayaran, 0, ',', '.') }}</div>
                            <small><i class="fas fa-users"></i> {{ $total_plg_pembayaran }} pelanggan</small>
                        </div>
                    </div>
                </div>
            </div>




            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card shadow-sm border-0 rounded-lg"
                    style="background: linear-gradient(135deg, #28a745, #b8d05f); color: white;">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-white rounded-circle d-flex align-items-center justify-content-center mr-3"
                            style="width: 50px; height: 50px;">
                            <img src="{{ asset('asset/img/icon/uang_karung.png') }}" height="30px">
                        </div>
                        <div>
                            <div class="font-weight-semibold mb-1">Tagihan Terbayar</div>
                            <div class="h5 mb-0 font-weight-bold">Rp {{ number_format($total_bayar, 0, ',', '.') }}
                            </div>
                            <small><i class="fas fa-user-check"></i> {{ $total_user_bayar }} user</small>
                        </div>
                    </div>
                </div>
            </div>




            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card shadow-sm border-0 rounded-lg"
                    style="background: linear-gradient(135deg, #dc3545, #d4d800); color: white;">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-white rounded-circle d-flex align-items-center justify-content-center mr-3"
                            style="width: 50px; height: 50px;">
                            <img src="{{ asset('asset/img/icon/sisa.png') }}" height="30px">
                        </div>
                        <div>
                            <div class="font-weight-semibold mb-1">Sisa Tagihan</div>
                            <div class="h5 mb-0 font-weight-bold">Rp
                                {{ number_format($belum_bayar, 0, ',', '.') }}</div>
                            <small><i class="fas fa-user-check"></i> {{ $total_user_belum }} user</small>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card shadow-sm border-0 rounded-lg"
                    style="background: linear-gradient(135deg, #6f42c1, #e83e8c); color: white;">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-white rounded-circle d-flex flex-column align-items-center justify-content-center mr-3 font-weight-bold"
                            style="width: 50px; height: 50px; font-size: 12px; color: #0066cc;">
                            <div>📊</div>
                            <div>
                                @if ($total_plg_pembayaran > 0)
                                    {{ round(($total_user_bayar / $total_plg_pembayaran) * 100) }}%
                                @else
                                    0%
                                @endif
                            </div>
                        </div>

                        <div>
                            <div class="font-weight-semibold mb-1">Tagihan Terbayar (Persentase)</div>
                            <div class="h5 mb-0 font-weight-bold">
                                {{ number_format(($total_bayar / $total_jml_pembayaran) * 100, 2, ',', '.') }}%
                            </div>
                            <small><i class="fas fa-user-check"></i>
                                @if ($total_plg_pembayaran > 0)
                                    {{ number_format(($total_user_bayar / $total_plg_pembayaran) * 100, 2, ',', '.') }}%
                                    user
                                @else
                                    0% user
                                @endif
                            </small>

                        </div>
                    </div>
                </div>
            </div>


            <!-- Chart Bar dan Line -->
            <div class="col-xl-70 col-lg-8 mb-3">
                <!-- Memperbesar tampilan card-body -->
                <div class="card-biru_tua" style="font-size: 1.5rem; height: 500px;">
                    <!-- Menambah ukuran font dan tinggi card -->
                    <div class="card-body" style="height: 100%;"> <!-- Memastikan card-body mengikuti tinggi card -->
                        <h6 class="text text-white font-weight-bold">Tabel Pembayaran Per Bulan</h6>
                        <!-- Membesarkan judul -->
                        <div class="chart-area" style="height: 400px;"> <!-- Menyesuaikan tinggi area chart -->
                            <canvas id="pendapatanChart" width="850" height="300"></canvas>
                            <!-- Untuk Bar/Line Chart -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-6 mb-3">
                <div class="card-biru_tua" style="font-size: 1.5rem; height: 500px;">
                    <div class="card-body flex-column " style="height: 100%;">
                        <h6 class="text-white font-weight-bold">PERSENTASE PEMBAYARAN</h6>


                        <div class="row p-3">
                            <!-- Tambahkan FontAwesome -->
                            <link rel="stylesheet"
                                href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

                            <table class="table table-bordered ">
                                <thead class="custom-cell warning">
                                    <tr>
                                        <th><i class="fas fa-wallet"></i> Total Bulanan</th>
                                        <th><i class="fas fa-hand-holding-usd"></i> Total Bayar</th>
                                        <th><i class="fas fa-money-bill-wave"></i> Total Sisa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="custom-cell primary">
                                            Rp {{ number_format($total_jml_pembayaran, 0, ',', '.') }} <i
                                                class="fas fa-users"></i>
                                            {{ number_format($total_plg_pembayaran, 0, ',', '.') }}
                                        </td>
                                        <td class="custom-cell primary">
                                            Rp {{ number_format($total_bayar, 0, ',', '.') }} <i
                                                class="fas fa-user-check"></i> {{ $total_user_bayar }}
                                        </td>
                                        <td class="custom-cell primary">
                                            Rp {{ number_format($belum_bayar, 0, ',', '.') }} <i
                                                class="fas fa-user-times"></i> {{ $total_user_belum }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="table table-bordered ">
                                <thead class="custom-cell warning">
                                    <tr>
                                        <th><i class="fas fa-file-invoice"></i> Tagihan Hari Ini</th>
                                        <th><i class="fas fa-hand-holding-usd"></i> Total Bayar</th>
                                        <th><i class="fas fa-money-bill"></i> Total Sisa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>

                                        <td class="custom-cell primary">
                                            <a href="{{ url('/pelanggan') }}?search=&tgl_tagih_plg[]={{ now()->format('d') }}&status_pembayaran=&bulan_pembayaran="
                                                style="display: block; color: inherit; text-decoration: none;">
                                                Rp {{ number_format($total_jml_pembayaran_harian, 0, ',', '.') }} <i
                                                    class="fas fa-user"></i>
                                                {{ number_format($total_plg_pembayaran_harian, 0, ',', '.') }}
                                            </a>
                                        </td>

                                        </td>
                                        <td class="custom-cell primary">
                                            <a href="{{ url('/pelanggan') }}?search=&tgl_tagih_plg[]={{ now()->format('d') }}&status_pembayaran=paid&bulan_pembayaran="
                                                style="display: block; color: inherit; text-decoration: none;">
                                                Rp {{ number_format($total_bayar_harian, 0, ',', '.') }} <i
                                                    class="fas fa-user-check"></i> {{ $total_user_bayar_harian }}
                                            </a>
                                        </td>
                                        <td class="custom-cell primary">
                                            <a href="{{ url('/pelanggan') }}?search=&tgl_tagih_plg[]={{ now()->format('d') }}&status_pembayaran=unpaid&bulan_pembayaran="
                                                style="display: block; color: inherit; text-decoration: none;">
                                                Rp {{ number_format($belum_sisa_bayar_harian, 0, ',', '.') }} <i
                                                    class="fas fa-user-clock"></i> {{ $total_user_sisa_harian }}
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>


                            <table class="table table-bordered ">
                                <thead class="custom-cell warning">
                                    <tr>
                                        <th><i class="fas fa-coins"></i> Uang Masuk Harian</th>
                                        <th><i class="fas fa-university"></i> Transfer (TF)</th>
                                        <th><i class="fas fa-cash-register"></i> CASH</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>

                                        <td class="custom-cell primary">
                                            <a href="/pembayaran/mudah/">
                                                Rp {{ number_format($total_TF, 0, ',', '.') }} <i
                                                    class="fas fa-mobile-alt"></i> {{ $total_user_tf }}</a>
                                        </td>
                                        <td class="custom-cell primary">
                                            Rp {{ number_format($total_TF, 0, ',', '.') }} <i
                                                class="fas fa-mobile-alt"></i> {{ $total_user_tf }}
                                        </td>
                                        <td class="custom-cell primary">
                                            Rp {{ number_format($total_cash, 0, ',', '.') }} <i
                                                class="fas fa-hand-holding"></i> {{ $total_user_cash }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>


                            <table class="table table-bordered">
                                <thead class="custom-cell warning">
                                    <tr>
                                        <!-- <th><i class="fas fa-chart-line"></i> Total Harian</th> -->
                                        <th><i class="fas fa-file-invoice-dollar"></i> Tagihan</th>
                                        <th><i class="fas fa-hand-holding-usd"></i> Piutang</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <!--     <td class="custom-cell primary">
                                                                                                                                        Rp {{ number_format($total_tagihan_piutang, 0, ',', '.') }} User:
                                                                                                                                        {{ $total_user_tagihan_piutang }}
                                                                                                                                    </td> -->
                                        <td class="custom-cell primary">
                                            Rp {{ number_format($uang_tagihan, 0, ',', '.') }} User:
                                            {{ number_format($user_tagihan, 0, ',', '.') }}
                                        </td>
                                        <td class="custom-cell primary">
                                            Rp {{ number_format($uang_piutang, 0, ',', '.') }} User: {{ $user_piutang }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="table table-bordered">
                                <thead class="custom-cell warning">
                                    <tr>
                                        <th><i class="fas fa-money-bill-wave"></i> Pengeluaran Harian</th>
                                        <th><i class="fas fa-coins"></i> Pemasukan Harian</th>
                                        <th><i class="fas fa-wallet"></i> Sisa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="custom-cell primary">
                                            Rp {{ number_format($pengeluaran_harian, 0, ',', '.') }}
                                        </td>
                                        <td class="custom-cell primary">
                                            Rp {{ number_format($pemasukan_harian, 0, ',', '.') }}
                                        </td>
                                        <td class="custom-cell primary">
                                            Rp {{ number_format($total_harian, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="table table-bordered">
                                <thead class="custom-cell warning">
                                    <tr>
                                        <th><i class="fas fa-tools"></i> Pemasangan Bulan Ini</th>
                                        <th><i class="fas fa-wrench"></i> Perbaikan Bulan Ini</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="custom-cell primary">
                                            <i class="fas fa-home"></i>
                                            {{ number_format($pemasangan_bulanan, 0, ',', '.') }}
                                        </td>
                                        <td class="custom-cell primary">
                                            <i class="fas fa-wrench"></i>
                                            {{ number_format($total_perbaikan, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-xl-70 col-lg-8 mb-3">
                <!-- Memperbesar tampilan card-body -->
                <div class="card-biru_tua" style="font-size: 1.5rem; height: 500px;">
                    <!-- Menambah ukuran font dan tinggi card -->
                    <div class="card-body" style="height: 100%;"> <!-- Memastikan card-body mengikuti tinggi card -->
                        <h6 class="text text-white font-weight-bold">Tabel Pemasangan Baru Per Bulan</h6>
                        <!-- Membesarkan judul -->
                        <div class="chart-area" style="height: 400px;"> <!-- Menyesuaikan tinggi area chart -->
                            <canvas id="pendapatanChart_PSB" width="850" height="300"></canvas>
                            <!-- Untuk Bar/Line Chart -->
                        </div>
                    </div>
                </div>
            </div>




            <div class="col-xl-4 col-lg-6 mb-3">
                <div class="card-biru_tua" style="font-size: 1.5rem; height: 500px;">
                    <div class="card-body d-flex flex-column" style="height: 100%;">
                        <h6 class="text-white font-weight-bold mb-3">Aktifitas Admin</h6>

                        {{-- Tabel ditaruh di atas --}}

                        <div class="table-responsive" style="max-height: 800px; overflow-y: auto;">
                            <table class="table table-sm table-bordered text-dark bg-white text-sm mb-0"
                                style="font-size: 11px;">

                                <thead class="custom-cell warning">
                                    <tr>
                                        <th class="p-1" style="width: 8%;">Tanggal</th>
                                        <th class="p-1" style="width: 8%;">Nama Admin</th>
                                        <th class="p-1" style="width: 8%;">Aktivitas</th>
                                        <th class="p-1" style="width: 8%;">Kategori</th>
                                        <th class="p-1" style="width: 8%;">Detail</th>
                                        <th class="p-1" style="width: 8%;">Alamat IP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($logs as $log)
                                        @php
                                            $details = json_decode($log->details);
                                        @endphp
                                        <tr>
                                            <td class="text-center p-1">{{ $log->created_at->format('d-m-Y H:i') }}</td>
                                            <td class="text-center p-1">{{ $log->user->name ?? 'Guest' }}</td>
                                            <td class="text-center p-1">{{ $log->activity }}</td>
                                            <td class="text-center p-1">{{ $log->module }}</td>
                                            <td class="text-center p-1">
                                                @php
                                                    $details = json_decode($log->details, true); // decode as array biar bisa ambil urutan
                                                    $firstTwo = array_slice($details, 1, 2); // ambil dua data pertama
                                                @endphp

                                                @foreach ($firstTwo as $key => $value)
                                                    {{ ucfirst($key) }}:
                                                    {{ is_numeric($value) ? $value : $value }}<br>
                                                @endforeach

                                            </td>

                                            <td class="text-center p-1">{{ $log->ip_address }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Jika ingin ada konten lain di bawah tabel, bisa ditambahkan di sini --}}
                    </div>
                </div>
            </div>


            <!--
                                                                                        <div class="col-xl-4 col-lg-6 mb-3">
                                                                                            <div class="card-biru_tua" style="font-size: 1.5rem; height: 500px;">
                                                                                                <div class="card-body d-flex flex-column justify-content-between" style="height: 100%;">
                                                                                                    <h6 class="text-white font-weight-bold">login User</h6>



                                                                                                    <div class="row p-3">

                                                                                                        <table class="table table-bordered text-white ">
                                                                                                            <thead class="custom-cell warning">
                                                                                                                <tr>
                                                                                                                    <th>Nama</th>
                                                                                                                    <th>Email</th>
                                                                                                                    <th>Terakhir Login</th>
                                                                                                                </tr>
                                                                                                            </thead>
                                                                                                            <tbody>
                                                                                                                @foreach ($users as $user)
    <tr>
                                                                                                                        <td>{{ $user->name }}</td>
                                                                                                                        <td>{{ $user->email }}</td>
                                                                                                                        <td>
                                                                                                                            {{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() : 'Belum pernah login' }}
                                                                                                                        </td>
                                                                                                                    </tr>
    @endforeach
                                                                                                            </tbody>
                                                                                                        </table>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    -->



            <div class="col-xl-4 col-lg-5 mt-4">
                <div class="card" style="height: 350px">

                    <div class="card-header bg-primary d-flex flex-row align-items-center justify-content-between p-2">
                        <h6 class="ml-2 font-weight-bold text-light" style="font-size: 14px;">
                            Total yang sudah hadir :
                            {{ $total_kehadiran }}
                        </h6>
                        <a class="m-0 float-right btn btn-danger btn-sm p-1" href="/x100c/show/"
                            style="font-size: 12px; font-weight: bold;">
                            Lihat semua <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>

                    <div class="text-center">
                        <table class="table table-bordered table-sm mx-auto">
                            <thead>
                                <tr>
                                    <th style="width: 7%; font-size: 12px; font-weight: bold;">No</th>
                                    <th style="width: 10%; font-size: 12px; font-weight: bold;">Nama</th>
                                    <th style="width: 10%; font-size: 12px; font-weight: bold;">Jam</th>
                                    <th style="width: 10%; font-size: 12px; font-weight: bold;">Status</th>
                                    <th style="width: 10%; font-size: 12px; font-weight: bold;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rekap_kehadiran_limited as $item)
                                    <tr class="table-success">
                                        <td class="text-center" style="font-size: 12px; font-weight: bold;">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td style="font-size: 12px; font-weight: bold;">{{ $item->nama }}</td>
                                        <td style="font-size: 12px; font-weight: bold;">{{ $item->waktu }}</td>
                                        <td style="font-size: 12px; font-weight: bold;">{{ $item->status }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-success">Available</button>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center table-success"
                                            style="font-size: 12px; font-weight: bold;">
                                            Tidak ada yang hadir hari ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>

                </div>
            </div>



            <div class="col-xl-4 col-lg-5 mt-2 ">
                <div class="card" style="height: 350px">


                    <div class="card-header bg-primary d-flex flex-row align-items-center justify-content-between p-2">
                        <h6 class="ml-2 font-weight-bold text-light" style="font-size: 14px;">
                            Pemberitahuan
                        </h6>
                        <a class="m-0 float-right btn btn-danger btn-sm p-1" href="/pemberitahuan"
                            style="font-size: 12px; font-weight: bold;">
                            Lihat semua <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                    <div>
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th style="width: 10%; font-size: 12px; fw-bold">No</th>

                                    <th style="width: 10%; font-size: 12px; fw-bold">Pesan</th>
                                    <th style="width: 10%; font-size: 12px; fw-bold">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pemberitahuan as $item)
                                    <tr>
                                        <td class="text-center" style="font-size: 12px; font-weight: bold;">
                                            {{ $loop->iteration }}</td>

                                        <td style="font-size: 12px; font-weight: bold;">{{ $item->pesan }}</td>
                                        <td style="font-size: 12px; font-weight: bold;">{{ $item->created_at }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" <td class="text-center"
                                            style="font-size: 12px; font-weight: bold;"> Tidak ada
                                            pemberitahuan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <div class="col-xl-4 col-lg-5 mt-2">
                <div class="card" style="height: 350px; font-size: 12px;">
                    <div class="card-header bg-primary d-flex flex-row align-items-center justify-content-between p-2">
                        <h6 class="ml-1 font-weight-bold text-light m-0" style="font-size: 12px;">
                            Pemasangan Bulan Ini (Total: <strong>{{ $pemasangan_bulanan }}</strong>)
                        </h6>
                        <a class="btn btn-danger btn-sm p-1" href="/rekap_pemasangan" style="font-size: 10px;">
                            Lihat semua <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                    <div class="table-responsive" style="max-height: 200px; overflow-y: auto;">
                        <table class="table table-sm table-bordered text-sm mb-0" style="font-size: 11px;">
                            <thead class="text-center">
                                <tr>
                                    <th class="p-1" style="width: 8%;">No</th>
                                    <th class="p-1" style="width: 25%;">Nama</th>
                                    <th class="p-1" style="width: 35%;">Alamat</th>
                                    <th class="p-1" style="width: 17%;">Tanggal</th>
                                    <th class="p-1" style="width: 15%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rekap_pemasangan_limited as $item)
                                    <tr>
                                        <td class="text-center p-1">{{ $loop->iteration }}</td>
                                        <td class="p-1">{{ $item->nama }}</td>
                                        <td class="p-1">{{ $item->alamat }}</td>
                                        <td class="text-center p-1">{{ $item->tgl_aktivasi }}</td>
                                        <td class="p-1 text-center">
                                            @if ($item->status == 'Proses')
                                                <form action="{{ route('psb.selesai', $item->id) }}" method="POST"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menyelesaikan PSB ini?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm p-1"
                                                        style="font-size: 9px;">Selesai</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center p-1">Tidak ada pemasangan bulan ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>



            <div class="col-xl-4 col-lg-5 mt-2">
                <div class="card" style="height: 350px; font-size: 12px;">
                    <div class="card-header bg-primary d-flex flex-row align-items-center justify-content-between p-2">
                        <h6 class="ml-1 font-weight-bold text-light m-0" style="font-size: 12px;">
                            Perbaikan Hari Ini (Total: {{ $total_perbaikan }})
                        </h6>
                        <a class="btn btn-danger btn-sm p-1" href="/perbaikan" style="font-size: 10px;">
                            Lihat semua <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                    <div class="table-responsive" style="max-height: 200px; overflow-y: auto;">
                        <table class="table table-sm table-bordered text-sm mb-0" style="font-size: 11px;">
                            <thead class="text-center">
                                <tr>
                                    <th class="p-1" style="width: 8%;">No</th>
                                    <th class="p-1" style="width: 25%;">Nama</th>
                                    <th class="p-1" style="width: 35%;">Alamat</th>
                                    <th class="p-1" style="width: 17%;">Tanggal</th>
                                    <th class="p-1" style="width: 15%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($perbaikan_limited as $item)
                                    <tr>
                                        <td class="text-center p-1">{{ $loop->iteration }}</td>
                                        <td class="p-1">{{ $item->nama_plg }}</td>
                                        <td class="p-1">{{ $item->alamat_plg }}</td>
                                        <td class="text-center p-1">{{ $item->created_at }}</td>
                                        <td class="p-1 text-center">
                                            @if ($item->status == 'Proses')
                                                <form action="{{ route('perbaikan.selesai', $item->id) }}" method="POST"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menyelesaikan perbaikan ini?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm p-1"
                                                        style="font-size: 9px;">Selesai</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center p-1">Tidak ada Perbaikan Hari ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <div class="col-xl-4 col-lg-5 mt-4">
                <div class="card" style="height: 350px">


                    <div class="card-header bg-primary d-flex flex-row align-items-center justify-content-between p-2">
                        <h6 class="ml-2 font-weight-bold text-light" style="font-size: 14px;">
                            Pengeluaran Hari Ini : {{ number_format($total_pengeluaran, 0, ',', '.') }}
                        </h6>
                        <a class="m-0 float-right btn btn-danger btn-sm p-1" href="/pengeluaran/"
                            style="font-size: 12px; font-weight: bold;">
                            Lihat semua <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>

                    <div>
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th style="width: 10%; font-size: 12px; fw-bold">No</th>
                                    <th style="width: 10%; font-size: 12px; fw-bold">Keterangan</th>
                                    <th style="width: 10%; font-size: 12px; fw-bold">Jumlah</th>
                                    <th style="width: 10%; font-size: 12px; fw-bold">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rekap_pengeluaran_limited as $item)
                                    <tr>
                                        <td class="text-center" style="font-size: 12px; font-weight: bold;">
                                            {{ $loop->iteration }}</td>
                                        <td style="font-size: 12px; font-weight: bold;">{{ $item->keterangan }}
                                        </td>
                                        <td style="font-size: 12px; font-weight: bold;">{{ $item->harga_total }}</td>
                                        <td style="font-size: 12px; font-weight: bold;">{{ $item->created_at }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" <td class="text-center"
                                            style="font-size: 12px; font-weight: bold;"> Tidak ada
                                            Pengeluaran hari ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-5 mt-4">
                <div class="card" style="height: 350px">


                    <div class="card-header bg-primary d-flex flex-row align-items-center justify-content-between p-2">
                        <h6 class="ml-2 font-weight-bold text-light" style="font-size: 14px;">
                            Pemasukan Hari Ini : {{ number_format($total_pemasukan, 0, ',', '.') }}
                        </h6>
                        <a class="m-0 float-right btn btn-danger btn-sm p-1" href="/pemasukan/"
                            style="font-size: 12px; font-weight: bold;">
                            Lihat semua <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>

                    <div>
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th style="width: 10%; font-size: 12px; fw-bold">No</th>
                                    <th style="width: 10%; font-size: 12px; fw-bold">Keterang<an /th>
                                    <th style="width: 10%; font-size: 12px; fw-bold">Jumlah</th>
                                    <th style="width: 10%; font-size: 12px; fw-bold">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rekap_pemasukan_limited as $item)
                                    <tr>
                                        <td class="text-center" style="font-size: 12px; font-weight: bold;">
                                            {{ $loop->iteration }}</td>
                                        <td style="font-size: 12px; font-weight: bold;">{{ $item->keterangan }}
                                        </td>
                                        <td style="font-size: 12px; font-weight: bold;">{{ $item->harga_total }}</td>
                                        <td style="font-size: 12px; font-weight: bold;">{{ $item->created_at }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" <td class="text-center"
                                            style="font-size: 12px; font-weight: bold;">Tidak ada
                                            pemasukan hari ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>



            <div class="col-xl-4 col-lg-5 mt-4">
                <div class="card" style="height: 350px">

                    <div class="card-header bg-primary d-flex flex-row align-items-center justify-content-between p-2">
                        <h6 class="ml-2 font-weight-bold text-light" style="font-size: 14px;">
                            Total Work Order:
                            {{ $total_WO }}
                        </h6>
                        <a class="m-0 float-right btn btn-danger btn-sm p-1" href="/x100c/show/"
                            style="font-size: 12px; font-weight: bold;">
                            Lihat semua <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>

                    <div>
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th style="width: 10%; font-size: 12px; fw-bold">No</th>
                                    <th style="width: 10%; font-size: 12px; fw-bold">Nama</th>
                                    <th style="width: 10%; font-size: 12px; fw-bold">Alamat</th>
                                    <th style="width: 10%; font-size: 12px; fw-bold">Tanggal</th>
                                    <th style="width: 10%; font-size: 12px; fw-bold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($Wo_tampil as $item)
                                    <tr>
                                        <td class="text-center" style="font-size: 12px; font-weight: bold;">
                                            {{ $loop->iteration }}</td>
                                        <td style="font-size: 12px; font-weight: bold;">{{ $item->nama_plg }}</td>
                                        <td style="font-size: 12px; font-weight: bold;">{{ $item->alamat_plg }}
                                        </td>
                                        <td style="font-size: 12px; font-weight: bold;">{{ $item->created_at }}
                                        </td>
                                        <td>
                                            @if ($item->status == 'Proses')
                                                <form action="{{ route('perbaikan.selesai', $item->id) }}" method="POST"
                                                    class="d-inline-block"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menyelesaikan perbaikan ini?')">
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn btn-success btn-sm">Selesaikan</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center"
                                            style="font-size: 12px; font-weight: bold;"> Tidak ada
                                            Work Order.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <div class="col-xl-4 col-lg-5 mt-4">
                <div class="card" style="height: 350px">

                    <div class="card-header bg-primary d-flex flex-row align-items-center justify-content-between p-2">
                        <h6 class="ml-2 font-weight-bold text-light" style="font-size: 14px;">
                            Stok Barang :

                        </h6>
                        <a class="m-0 float-right btn btn-danger btn-sm p-1" href="/x100c/show/"
                            style="font-size: 12px; font-weight: bold;">
                            Lihat semua <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>

                    <div class="">
                        @php
                            $groupedInventories = $inventories->groupBy('kategori');
                            $totalKeseluruhan = $inventories->sum('jml_brg');
                        @endphp
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered text-sm">
                                <thead class="table text-black">
                                    <tr>
                                        <th class="p-1">No</th>
                                        <th class="p-1">Kategori</th>
                                        <th class="p-1">Persentase (%)</th>
                                        <th class="p-1">Total Stok</th>
                                        <th class="p-1">Harga Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($groupedInventories as $kategori => $items)
                                        @php
                                            $totalJumlahKategori = $items->sum('jml_brg');
                                            $totalHargaKategori = $items->sum('harga_total');
                                            $persentaseKategori =
                                                $totalKeseluruhan > 0
                                                    ? round(($totalJumlahKategori / $totalKeseluruhan) * 100)
                                                    : 0;
                                        @endphp
                                        <tr>
                                            <td class="p-1">{{ $loop->iteration }}</td> <!-- Tambahan Nomor Urut -->
                                            <td class="p-1">{{ $kategori }}</td>
                                            <td class="p-1 text-center">{{ $persentaseKategori }}%</td>
                                            <td class="p-1 text-center">{{ $totalJumlahKategori }}
                                                {{ $items->first()->satuan ?? 'PCS' }}</td>
                                            <td class="p-1 text-right">
                                                Rp{{ number_format($totalHargaKategori, 2, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th class="p-1 text-center" colspan="2">Keseluruhan</th>
                                        <th class="p-1 text-center">100%</th>
                                        <td class="p-1 text-center">{{ $inventories->sum('jml_brg') }}
                                            {{ $inventories->first()->satuan ?? 'PCS' }}</td>
                                        <th class="p-1 text-right">
                                            Rp{{ number_format($inventories->sum('harga_total'), 2, ',', '.') }}</th>
                                    </tr>
                                </tfoot>
                            </table>


                        </div>
                    </div>
                </div>
            </div>



            <style>
                /* Mengecilkan font dan padding tabel */
                .table {
                    font-size: 12px;
                }

                .table th,
                .table td {
                    padding: 5px;
                    text-align: center;
                }

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




            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

            <script>
                // Data dari backend (controller Laravel) untuk chart batang/garis
                const labels = @json($labels); // Label untuk tanggal (1-30)
                const totalUsers = @json($totalUsers); // Jumlah user per tanggal
                const totalPembayaran = @json($totalPembayaran); // Total pembayaran per tanggal

                // Inisialisasi Bar dan Line Chart
                const ctx1 = document.getElementById('pendapatanChart').getContext('2d');
                const pendapatanChart = new Chart(ctx1, {
                    type: 'bar', // Tipe chart batang (bar)
                    data: {
                        labels: labels, // Label (Tanggal 1-30)
                        datasets: [{
                                label: 'Jumlah Pengguna Bayar',
                                data: totalUsers, // Data jumlah pengguna per hari
                                backgroundColor: 'rgba(255, 255, 255, 0.2)', // Warna batang putih semi transparan
                                borderColor: 'rgb(255, 157, 0)', // Warna border batang putih
                                borderWidth: 1,
                                pointRadius: 8, // Mengatur ukuran titik (besar)
                                pointHoverRadius: 10 // Mengatur ukuran titik saat di-hover (lebih besar)
                            },
                            {
                                label: 'Total Pembayaran (Rp)',
                                data: totalPembayaran, // Data total pembayaran per hari
                                backgroundColor: 'rgba(255, 255, 255, 0.2)', // Warna grafik garis putih semi transparan
                                borderColor: 'rgb(255, 157, 0)', // Warna border garis putih
                                borderWidth: 1,
                                type: 'line', // Grafik tipe garis (line)
                                pointRadius: 8, // Mengatur ukuran titik untuk garis (besar)
                                pointHoverRadius: 10 // Mengatur ukuran titik saat di-hover (lebih besar)
                            }
                        ]
                    },
                    options: {
                        layout: {
                            padding: 0 // Menghilangkan padding pada layout
                        },
                        scales: {
                            y: {
                                beginAtZero: true, // Memulai grafik dari 0 pada sumbu Y
                                ticks: {
                                    color: 'white' // Warna label sumbu Y menjadi putih
                                },
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.2)' // Warna grid sumbu Y putih semi transparan
                                }
                            },
                            x: {
                                ticks: {
                                    color: 'white' // Warna label sumbu X menjadi putih
                                },
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.2)' // Warna grid sumbu X putih semi transparan
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                labels: {
                                    color: 'white', // Warna label legenda menjadi putih
                                    font: {
                                        style: 'bold' // Membuat label legenda bold
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(255, 255, 255, 0.8)', // Background tooltip putih semi transparan
                                titleColor: 'black', // Warna judul tooltip hitam
                                bodyColor: 'black', // Warna isi tooltip hitam
                                borderColor: 'rgba(255, 255, 255, 1)', // Warna border tooltip putih
                                borderWidth: 1,
                                titleFont: {
                                    weight: 'bold' // Membuat teks judul tooltip bold
                                }
                            }
                        }
                    }
                });


                // Data untuk Pie Chart
                var totalTagihanHariIni = @json($totalTagihanHariIni); // Total tagihan hari ini
                var totalPendapatanharian_semua = @json($totalPendapatanharian_semua); // Total pendapatan harian semua

                // Inisialisasi Pie Chart
                var ctx2 = document.getElementById("myPieChart").getContext('2d');
                var myPieChart = new Chart(ctx2, {
                    type: 'pie', // Menggunakan tipe pie untuk lingkaran penuh
                    data: {
                        datasets: [{
                            data: [totalTagihanHariIni - totalPendapatanharian_semua,
                                totalPendapatanharian_semua
                            ], // Data dari controller
                            backgroundColor: ['#e74c3c', '#FFBB00'], // Warna untuk bagian chart
                            hoverBackgroundColor: ['#c0392b', '#FFBB00'], // Warna saat di-hover
                            hoverBorderColor: "rgba(234, 236, 244, 1)", // Border saat di-hover
                        }],
                    },
                    options: {
                        maintainAspectRatio: false,
                        tooltips: {
                            backgroundColor: "rgb(255,255,255)",
                            titleFontColor: "white", // Judul tooltip putih
                            bodyFontColor: "white", // Isi tooltip putih
                            titleFontStyle: "bold", // Judul tooltip bold
                            bodyFontStyle: "bold", // Isi tooltip bold
                            borderColor: '#dddfeb',
                            borderWidth: 1,
                            xPadding: 15,
                            yPadding: 15,
                            displayColors: false,
                            caretPadding: 10,
                            callbacks: {
                                label: function(tooltipItem, data) {
                                    var dataset = data.datasets[tooltipItem.datasetIndex];
                                    var total = dataset.data.reduce(function(previousValue, currentValue) {
                                        return previousValue + currentValue;
                                    });
                                    var currentValue = dataset.data[tooltipItem.index];
                                    var percentage = Math.floor(((currentValue / total) * 100) + 0.5);
                                    return data.labels[tooltipItem.index] + ': ' + percentage + '%';
                                }
                            }
                        },
                        legend: {
                            display: true, // Tampilkan legenda untuk menjelaskan chart
                            position: 'bottom', // Posisi legenda di bawah chart
                            labels: {
                                fontColor: "white", // Warna teks legenda menjadi putih
                                fontStyle: "bold", // Teks legenda menjadi bold
                                usePointStyle: true // Menjaga ikon lingkaran di legend
                            }
                        },
                        cutoutPercentage: 0, // Tidak ada ruang di tengah lingkaran (untuk pie chart penuh)
                        plugins: {
                            labels: {
                                render: 'label',
                                fontColor: 'white', // Membuat label chart menjadi putih
                                fontStyle: 'bold' // Membuat teks label chart menjadi bold
                            }
                        }
                    }
                });



                //Target Marketing
                // Data untuk Pie Chart
                var sisa_target = @json($sisa_target); // Total tagihan hari ini
                var jumlah_target = @json($jumlah_target); // Total pendapatan harian semua

                // Inisialisasi Pie Chart
                var ctx2 = document.getElementById("myPieChart1").getContext('2d');
                var myPieChart1 = new Chart(ctx2, {
                    type: 'pie', // Menggunakan tipe pie untuk lingkaran penuh
                    data: {
                        datasets: [{
                            data: [sisa_target - jumlah_target,
                                jumlah_target
                            ], // Data dari controller
                            backgroundColor: ['#00BFFF', '#0000CD '], // Warna untuk bagian chart
                            hoverBackgroundColor: ['#00BFFF', '#0000CD '], // Warna saat di-hover
                            hoverBorderColor: "rgba(234, 236, 244, 1)", // Border saat di-hover
                        }],
                    },
                    options: {
                        maintainAspectRatio: false,
                        tooltips: {
                            backgroundColor: "rgb(255,255,255)",
                            titleFontColor: "white", // Judul tooltip putih
                            bodyFontColor: "white", // Isi tooltip putih
                            titleFontStyle: "bold", // Judul tooltip bold
                            bodyFontStyle: "bold", // Isi tooltip bold
                            borderColor: '#dddfeb',
                            borderWidth: 1,
                            xPadding: 15,
                            yPadding: 15,
                            displayColors: false,
                            caretPadding: 10,
                            callbacks: {
                                label: function(tooltipItem, data) {
                                    var dataset = data.datasets[tooltipItem.datasetIndex];
                                    var total = dataset.data.reduce(function(previousValue, currentValue) {
                                        return previousValue + currentValue;
                                    });
                                    var currentValue = dataset.data[tooltipItem.index];
                                    var percentage = Math.floor(((currentValue / total) * 100) + 0.5);
                                    return data.labels[tooltipItem.index] + ': ' + percentage + '%';
                                }
                            }
                        },
                        legend: {
                            display: true, // Tampilkan legenda untuk menjelaskan chart
                            position: 'bottom', // Posisi legenda di bawah chart
                            labels: {
                                fontColor: "white", // Warna teks legenda menjadi putih
                                fontStyle: "bold", // Teks legenda menjadi bold
                                usePointStyle: true // Menjaga ikon lingkaran di legend
                            }
                        },
                        cutoutPercentage: 0, // Tidak ada ruang di tengah lingkaran (untuk pie chart penuh)
                        plugins: {
                            labels: {
                                render: 'label',
                                fontColor: 'white', // Membuat label chart menjadi putih
                                fontStyle: 'bold' // Membuat teks label chart menjadi bold
                            }
                        }
                    }
                });
            </script>




        </div>

        <!-- Modal Logout -->
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabelLogout" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabelLogout">Ohh No!</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to logout?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Cancel</button>
                        <a href="/logout" class="btn btn-primary">Logout</a>
                    </div>
                </div>
            </div>
        </div>



        <!-- Footer -->
        <br><br>
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>copyright &copy;
                        <script>
                            document.write(new Date().getFullYear());
                        </script> - developed by
                        <b><a href="" target="_blank">Tiara Net</a></b>
                    </span>
                </div>
            </div>
        </footer>
        </footer>
    @endsection


    <!-- CSS untuk Running Text -->
    <style>
        .running-text-container {
            background-color: rgb(48, 48, 48);
            /* Mengubah latar belakang menjadi hitam */
            color: white;
            /* Mengubah warna teks menjadi putih untuk kontras yang baik */
            overflow: hidden;
            white-space: nowrap;
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            height: 50px;
            /* Tinggi kontainer */
        }

        .running-text {
            display: inline-block;
            font-size: 1.2rem;
            /* Sedikit memperbesar ukuran font */
            animation: scroll-left 20s linear infinite;
            /* Durasi animasi tetap */
        }

        @keyframes scroll-left {
            0% {
                transform: translateX(100%);
            }

            100% {
                transform: translateX(-100%);
            }
        }
    </style>

    <!-- HTML untuk Running Text dengan Data -->
    <div class="running-text-container">
        <div class="running-text">
            <span>
                Sisa Tagihan Hari Ini: Rp {{ number_format($totalTagihanTertagih, 0, ',', '.') }} User:
                {{ $totalUserTertagih }} ||
                Tagihan Hari Ini: Rp {{ number_format($totalTagihanHariIni, 0, ',', '.') }} User:
                {{ $jumlahPelangganMembayarHariIni }} ||
                Tertagih: Rp {{ number_format($totalPendapatanharian_semua, 0, ',', '.') }} User:
                {{ $totalUserHarian_semua }}
            </span>
        </div>
    </div>


    <script>
        // Auto-refresh halaman setiap 30 detik
        setInterval(() => {
            location.reload(); // Reload halaman
        }, 100000); // 30.000 ms = 30 detik
    </script>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            const labels = @json($labels); // Label untuk tanggal (1-30)
            const totalPemasangan = @json($totalPemasangan); // Jumlah pemasangan per tanggal
            const totalPendapatan = @json($totalPendapatan); // Total pendapatan per tanggal

            // Inisialisasi Bar dan Line Chart
            const ctx2 = document.getElementById('pendapatanChart_PSB').getContext('2d');
            const pendapatanChartPSB = new Chart(ctx2, {
                type: 'bar', // Tipe chart batang (bar)
                data: {
                    labels: labels, // Label (Tanggal 1-30)
                    datasets: [{
                            label: 'Total Pemasangan',
                            data: totalPemasangan, // Data jumlah pemasangan per hari
                            backgroundColor: 'rgba(255, 255, 255, 0.2)', // Warna batang putih semi transparan
                            borderColor: 'rgb(255, 157, 0)', // Warna border batang oranye
                            borderWidth: 1,
                            pointRadius: 8, // Ukuran titik lebih besar
                            pointHoverRadius: 10 // Ukuran titik saat di-hover lebih besar
                        },
                        {
                            label: 'Total Pendapatan (Rp)',
                            data: totalPendapatan, // Data total pendapatan per hari
                            backgroundColor: 'rgba(255, 255, 255, 0.2)', // Warna grafik garis putih semi transparan
                            borderColor: 'rgb(255, 157, 0)', // Warna border garis oranye
                            borderWidth: 1,
                            type: 'line', // Grafik tipe garis (line)
                            pointRadius: 8, // Ukuran titik untuk garis lebih besar
                            pointHoverRadius: 10 // Ukuran titik saat di-hover lebih besar
                        }
                    ]
                },
                options: {
                    layout: {
                        padding: 0 // Menghilangkan padding pada layout
                    },
                    scales: {
                        y: {
                            beginAtZero: true, // Memulai grafik dari 0 pada sumbu Y
                            ticks: {
                                color: 'white' // Warna label sumbu Y menjadi putih
                            },
                            grid: {
                                color: 'rgba(255, 255, 255, 0.2)' // Warna grid sumbu Y putih semi transparan
                            }
                        },
                        x: {
                            ticks: {
                                color: 'white' // Warna label sumbu X menjadi putih
                            },
                            grid: {
                                color: 'rgba(255, 255, 255, 0.2)' // Warna grid sumbu X putih semi transparan
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: {
                                color: 'white', // Warna label legenda menjadi putih
                                font: {
                                    style: 'bold' // Membuat label legenda bold
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.8)', // Background tooltip putih semi transparan
                            titleColor: 'black', // Warna judul tooltip hitam
                            bodyColor: 'black', // Warna isi tooltip hitam
                            borderColor: 'rgba(255, 255, 255, 1)', // Warna border tooltip putih
                            borderWidth: 1,
                            titleFont: {
                                weight: 'bold' // Membuat teks judul tooltip bold
                            }
                        }
                    }
                }
            });
        });
    </script>
