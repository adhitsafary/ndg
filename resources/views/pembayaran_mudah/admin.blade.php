<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bayar tagihan Pelanggan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .copy-btn {
            cursor: pointer;
            color: blue;
            text-decoration: underline;
            border: none;
            background: none;
            padding: 0;
            font-size: 0.9em;
        }
    </style>
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Angka berhasil disalin ke clipboard!');
            }).catch(err => {
                alert('Gagal menyalin teks: ' + err);
            });
        }

        function showDetail(numbers) {
            const container = document.getElementById('detailContainer');
            container.innerHTML = '';

            numbers.forEach((num, index) => {
                if (index % 5 === 0) {
                    const row = document.createElement('div');
                    row.className = 'row mb-3';
                    container.appendChild(row);
                }

                const col = document.createElement('div');
                col.className = 'col-md-6';
                col.innerHTML = `
                    <div class="p-2 border text-center">
                        <strong>${index + 1}.</strong> ${num}
                        <button onclick="copyToClipboard('${num}')" class="btn btn-sm btn-link copy-btn">Salin</button>
                    </div>`;
                container.lastChild.appendChild(col);
            });

            const modal = new bootstrap.Modal(document.getElementById('detailModal'));
            modal.show();
        }

        function confirmDelete(quantity) {
            return confirm(`Anda akan menghapus data dengan jumlah angka: ${quantity}. Lanjutkan?`);
        }
    </script>
</head>

<br>
<br>

<body>
    <div class="container">
        <h4 class="mb-4 mt-5 text-center">Bayar Tagihan Pelanggan</h4> <br>

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

        <form action="{{ route('pembayaran_mudah.admin') }}">
            <div class="form-group d-flex">
                <input type="text" name="q" class="form-control me-2 "
                    placeholder="Cari berdasarkan ID atau Nama" value="{{ $query ?? '' }}">


                <button type="submit" class="btn btn-primary w-50 ml-2">Cari / Refresh</button>

            </div>
        </form>

        <div class="mt-4">
            @if (!$query_cari)
                <p class="text-muted">Silakan masukkan ID atau Nama untuk mencari data pelanggan.</p>

                <div>
                @elseif($pelanggan->isEmpty())
                    <p class="text-muted">Tidak ditemukan hasil untuk "{{ $query_cari }}"</p>
                @else
                    <table class="table table-bordered table-responsive"
                        style="color: black; width: 100%; font-size: 0.9em; table-layout: fixed;">
                        <thead class="table table-primary" style="color: black;">
                            <tr>
                                <th class="text-center" style="width: 1%; padding: 1px;">No</th>
                                <th class="text-center" style="width: 1%; padding: 1px;">ID Pelanggan</th>
                                <th class="text-center" style="width: 1%; padding: 1px;">Nama</th>
                                <th class="text-center" style="width: 1%; padding: 1px;">Alamat</th>
                                <th class="text-center" style="width: 1%; padding: 1px;">Harga</th>
                                <th class="text-center" style="width: 1%; padding: 1px;">Tanggal Tagih</th>
                                <th class="text-center" style="width: 1%; padding: 1px;">Status Pembayaran</th>
                                <th class="text-center" style="width: 1%; padding: 1px;">Total</th>
                                <th class="text-center" style="width: 1%; padding: 1px;">Bayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pelanggan as $no => $item)
                                <tr>
                                    <td class="text-center" style="padding: 1px;">
                                        {{ ($pelanggan->currentPage() - 1) * $pelanggan->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="text-center" style="padding: 1px;">{{ $item->id_plg }}</td>
                                    <td class="text-center" style="padding: 1px;">{{ $item->nama_plg }}</td>
                                    <td class="text-center" style="padding: 1px;">{{ $item->alamat_plg }}</td>
                                    <td class="text-center" style="padding: 1px;">
                                        {{ number_format($item->harga_paket, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center" style="padding: 1px;">{{ $item->tgl_tagih_plg }}</td>
                                    <td class="text-center" style="padding: 1px;">
                                        @php
                                            $bulanTerlewat = [];
                                            $warnaBadge = [
                                                'primary',

                                                'success',
                                                'danger',
                                                'warning',
                                                'secondary',
                                                'info',
                                                'dark',
                                            ];
                                            $tglBayarTerakhir = optional($item->pembayaranTerakhir)->tanggal_pembayaran;
                                            $tglAktivasi = $item->aktivasi_plg;
                                            $sekarang = \Carbon\Carbon::now()->startOfMonth();

                                            try {
                                                if (
                                                    $tglBayarTerakhir &&
                                                    \Carbon\Carbon::hasFormat($tglBayarTerakhir, 'Y-m-d')
                                                ) {
                                                    $mulaiDari = \Carbon\Carbon::parse($tglBayarTerakhir)
                                                        ->addMonth()
                                                        ->startOfMonth();
                                                } elseif (\Carbon\Carbon::hasFormat($tglAktivasi, 'Y-m-d')) {
                                                    $mulaiDari = \Carbon\Carbon::parse($tglAktivasi)->startOfMonth();
                                                } else {
                                                    $mulaiDari = null;
                                                }

                                                if ($mulaiDari && $mulaiDari <= $sekarang) {
                                                    while ($mulaiDari <= $sekarang) {
                                                        $bulanTerlewat[] = $mulaiDari->isoFormat('MMMM Y');
                                                        $mulaiDari->addMonth();
                                                    }
                                                }
                                            } catch (\Exception $e) {
                                                $bulanTerlewat = [];
                                            }
                                        @endphp

                                        <div>
                                            <strong class="text-white">Terakhir Bayar:</strong><br>
                                            {{ $tglBayarTerakhir ? \Carbon\Carbon::parse($tglBayarTerakhir)->locale('id')->isoFormat('MMMM Y') : 'PSB' }}
                                        </div>

                                        <div class="mt-2">
                                            <strong class="text-white">Bulan Terlewat:</strong><br>
                                            @if (count($bulanTerlewat) > 0)
                                                @foreach ($bulanTerlewat as $index => $bulan)
                                                    @php $warna = $warnaBadge[$index % count($warnaBadge)]; @endphp
                                                    <span
                                                        class="badge bg-{{ $warna }} text-white mb-1">{{ $bulan }}</span><br>
                                                @endforeach
                                            @else
                                                <span class="text-muted">Tidak Ada Tunggakan</span>
                                            @endif
                                        </div>
                                    </td>

                                    @php
                                        $lastPaymentDate = optional($item->pembayaranTerakhir)->tanggal_pembayaran;
                                        $lastPaymentMonth = $lastPaymentDate
                                            ? \Carbon\Carbon::parse($lastPaymentDate)->startOfMonth()
                                            : null;

                                        $currentMonth = \Carbon\Carbon::now()->startOfMonth();

                                        // Hitung jumlah bulan yang belum dibayar
                                        $unpaidMonths = $lastPaymentMonth
                                            ? $lastPaymentMonth->diffInMonths($currentMonth)
                                            : 0;

                                        // Hitung total tunggakan
                                        $totalTunggakan = $unpaidMonths * $item->harga_paket;
                                    @endphp

                                    <td class="text-center" style="padding: 1px;">
                                        {{ number_format($totalTunggakan, 0, ',', '.') }}
                                    </td>

                                    <td class="text-center" style="padding: 0; margin: 0; text-align: center;">
                                        <a href="#" class="btn btn-success btn-xs" class="text-center"
                                            style="padding: 2px 5px; font-size: 0.75em;"
                                            onclick="showBayarModal({{ $item->id }}, '{{ $item->nama_plg }}', {{ $item->harga_paket }})">
                                            <img src="{{ asset('asset/img/icon/bayar.png') }}" class="text-center"
                                                style="height : 30px; width : 30px; " alt=""></a>
                                    </td>
                                    <!-- Modal Bayar -->
                                    <div class="modal fade" id="bayarModal" tabindex="-1"
                                        aria-labelledby="bayarModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="bayarModalLabel">Pembayaran</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <!-- Modal Form -->
                                                <form id="bayarForm" method="POST">
                                                    @csrf
                                                    @method('POST')

                                                    @csrf
                                                    <input type="hidden" name="id" id="pelangganId">
                                                    <div class="modal-body">
                                                        <!-- Input Tanggal Pembayaran -->
                                                        <div class="mb-3">
                                                            <label for="tanggal_pembayaran" class="form-label">Untuk
                                                                Pembayaran
                                                                Bulan</label>
                                                            <input type="month" class="form-select"
                                                                id="tanggal_pembayaran" name="tanggal_pembayaran"
                                                                placeholder="Pilih bulan">
                                                        </div>
                                                        @php
                                                            $role = Auth::user()->role ?? 'guest';
                                                        @endphp

                                                        <div class="mb-3">
                                                            <label for="metodeTransaksi" class="form-label">Metode
                                                                Transaksi</label>
                                                            <select class="form-select" id="metodeTransaksi"
                                                                name="metode_transaksi" required>
                                                                <option value="">Pilih metode</option>

                                                                @if ($role == 'admin')
                                                                    <option value="CASH">KANTOR</option>
                                                                @elseif ($role == 'finance')
                                                                    <option value="TF">TF</option>
                                                                @elseif ($role == 'superadmin')
                                                                    <option value="TF">TF</option>
                                                                    <option value="CASH">KANTOR</option>
                                                                @endif
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="untuk_pembayaran" class="form-label">Status
                                                                Pembayaran</label>
                                                            <select class="form-select" id="untuk_pembayaran"
                                                                name="untuk_pembayaran" required>
                                                                <option value="">Pilih Pembayaran</option>
                                                                <option value="tagihan">Tagihan </option>
                                                                <option value="piutang">Piutang </option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="nm_pengirim" class="form-label">Nama
                                                                Pengirim</label>
                                                            <input type="text" class="form-control" required
                                                                id="nm_pengirim" name="nm_pengirim">

                                                            <div class="row mb-3 align-items-end">
                                                                <div class="col-md-4">
                                                                    <label for="tanggal" class="form-label">Tanggal
                                                                        Kirim</label>
                                                                    <input type="date" class="form-control"
                                                                        id="tanggal" required>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label for="jam"
                                                                        class="form-label">Jam</label>
                                                                    <select id="jam" class="form-control"
                                                                        required>
                                                                        <option value="">-- Pilih Jam --</option>
                                                                        @for ($i = 1; $i <= 24; $i++)
                                                                            <option
                                                                                value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">
                                                                                {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                                                            </option>
                                                                        @endfor
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label for="menit"
                                                                        class="form-label">Menit</label>
                                                                    <select id="menit" class="form-control"
                                                                        required>
                                                                        <option value="">-- Pilih Menit --
                                                                        </option>
                                                                        @for ($i = 1; $i <= 59; $i++)
                                                                            <option
                                                                                value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">
                                                                                {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                                                            </option>
                                                                        @endfor
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <!-- Input tersembunyi yang akan diisi otomatis -->
                                                            <input type="hidden" name="tgl_kirim" id="tgl_kirim">

                                                            <script>
                                                                // Gabungkan tanggal + jam + menit ke input hidden
                                                                function gabungTglJamMenit() {
                                                                    const tanggal = document.getElementById('tanggal').value;
                                                                    const jam = document.getElementById('jam').value;
                                                                    const menit = document.getElementById('menit').value;

                                                                    if (tanggal && jam && menit) {
                                                                        const gabungan = `${tanggal} ${jam}:${menit}:00`;
                                                                        document.getElementById('tgl_kirim').value = gabungan;
                                                                    }
                                                                }

                                                                // Jalankan setiap kali ada perubahan
                                                                document.getElementById('tanggal').addEventListener('change', gabungTglJamMenit);
                                                                document.getElementById('jam').addEventListener('change', gabungTglJamMenit);
                                                                document.getElementById('menit').addEventListener('change', gabungTglJamMenit);
                                                            </script>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="keterangan_plg" class="form-label">Keterangan
                                                                Pembayaran Pelanggan</label>
                                                            <input type="text" class="form-control"
                                                                id="keterangan_plg" name="keterangan_plg">
                                                        </div>

                                                        <!-- Detail Pembayaran -->
                                                        <div class="mb-3">
                                                            <p id="pembayaranDetails"></p>
                                                        </div>
                                                    </div>

                                                    <!-- Modal Footer -->
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Bayar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

            @endif
        </div>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function showBayarModal(id, namaPlg, hargaPaket) {
            document.getElementById('pelangganId').value = id;
            document.getElementById('pembayaranDetails').innerText =
                `Nama Pelanggan: ${namaPlg}\nHarga Paket: Rp. ${hargaPaket}\n`;

            var form = document.getElementById('bayarForm');
            form.action = `/pelanggan/${id}/bayar_mudah_hp`; // Pastikan route benar
            form.method = "POST"; // Tambahkan ini agar metode POST digunakan

            var bayarModal = new bootstrap.Modal(document.getElementById('bayarModal'));
            bayarModal.show();
        }
    </script>

</body>

</html>
