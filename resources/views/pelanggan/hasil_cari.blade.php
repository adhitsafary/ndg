@extends($layout) {{-- atau layout yang kamu pakai --}}
@section('konten')

    <div class="card m-3">
        <h4 class="text-black">Hasil Pencarian untuk: "{{ $query }}"</h4>

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

        <!-- Modal Loading -->
        <div class="modal fade" id="loadingModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content text-center">
                    <div class="modal-body">
                        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden"></span>
                        </div>
                        <p class="mt-3">Sedang Memproses...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Sukses -->
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
                        <h3 class="text-success">✔</h3> <!-- Ikon besar -->
                        <p id="successMessage" class="mt-2"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Gagal -->
        <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="errorModalLabel">
                            <span class="me-2">❌</span> Gagal!
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <h3 class="text-danger">✖</h3> <!-- Ikon besar -->
                        <p id="errorMessage" class="mt-2"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="mt-2">
            @if (!$query)
                <p class="text-muted mr-5 ml-5">Silakan masukkan ID atau Nama untuk mencari data pelanggan.</p>

                <div>
                    <!-- Tabel Pembayaran -->
                    <div class=" ">
                        <div class="row">
                            @forelse ($pembayaran as $no => $item)
                                <div class="col-12 col-md-6 col-lg-4 mb-3">
                                    <div class="card shadow-sm">
                                        <div class="card-body">
                                            <h5 class="card-title">
                                                Pelanggan
                                                {{ ($pembayaran->currentPage() - 1) * $pembayaran->perPage() + $loop->iteration }}
                                            </h5>


                                            <p class="card-text">

                                                <strong>Nama:</strong> {{ $item->nama_plg }}<br>
                                                <strong>Alamat:</strong> {{ $item->alamat_plg }}<br>
                                                <strong>Tanggal Tagih:</strong> {{ $item->tgl_tagih_plg }}<br>
                                                <strong>Harga:</strong> Rp
                                                {{ number_format($item->jumlah_pembayaran, 0, ',', '.') }}<br>
                                                <strong>Metode Pembayaran:</strong> {{ $item->metode_transaksi }}<br>
                                                <strong>Tanggal Pembayaran:</strong> {{ $item->created_at }}<br>
                                                <strong>Keterangan:</strong> {{ $item->untuk_pembayaran }}<br>
                                                <strong>Admin:</strong> {{ $item->admin_name }}
                                            </p>
                                            <div class="d-flex justify-content-end">
                                                <form action="{{ route('pembayaran_hp.destroy', $item->id) }}"
                                                    method="POST" class="d-inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a href="#"
                                                        onclick="if(confirm('Yakin ingin menghapus data ini?')) { this.closest('form').submit(); return false; }"
                                                        style="display: inline-block;">
                                                        <img src="{{ asset('asset/img/icon/delete.png') }}"
                                                            style="height: 35px; width: 35px;" alt="Hapus">
                                                    </a>
                                                </form>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center">
                                    <p>Tidak ada data pembayaran ditemukan</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>
                <div>
                @elseif($pelanggan->isEmpty())

                @else
                    <div class="row">
                        @foreach ($pelanggan as $no => $item)
                            <div class="col-12 col-md-6 col-lg-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            Pelanggan
                                            {{ $loop->iteration }}
                                        </h5>
                                        <p class="card-text">
                                            <strong>ID Pelanggan:</strong> {{ $item->id_plg }}<br>
                                            <strong>Nama:</strong> {{ $item->nama_plg }}<br>
                                            <strong>Alamat:</strong> {{ $item->alamat_plg }}<br>
                                            <strong>Harga:</strong> Rp
                                            {{ number_format($item->harga_paket, 0, ',', '.') }}<br>
                                            <strong>Tanggal Tagih:</strong> {{ $item->tgl_tagih_plg }}<br>
                                            <strong>Status Pembayaran:</strong>
                                            {{ optional($item->pembayaranTerakhir)->tanggal_pembayaran
                                                ? \Carbon\Carbon::parse($item->pembayaranTerakhir->tanggal_pembayaran)->locale('id')->isoFormat('MMMM Y')
                                                : '-' }}
                                        </p>

                                        {{-- Hitung bulan terlewat --}}
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

                                        <div class="mb-2">
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

                                        {{-- Hitung total tunggakan --}}
                                        @php
                                            $lastPaymentDate = optional($item->pembayaranTerakhir)->tanggal_pembayaran;
                                            $lastPaymentMonth = $lastPaymentDate
                                                ? \Carbon\Carbon::parse($lastPaymentDate)->startOfMonth()
                                                : null;
                                            $currentMonth = \Carbon\Carbon::now()->startOfMonth();
                                            $unpaidMonths = $lastPaymentMonth
                                                ? $lastPaymentMonth->diffInMonths($currentMonth)
                                                : 0;
                                            $totalTunggakan = $unpaidMonths * $item->harga_paket;
                                        @endphp

                                        <p>Total Tunggakan:
                                            <strong class=" fw-bold"> Rp
                                                {{ number_format($totalTunggakan, 0, ',', '.') }}</strong>
                                        </p>

                                        {{-- Tombol Bayar --}}
                                        <div class="text-center">
                                            <a href="#" class="btn btn-success btn-xs"
                                                style="padding: 2px 5px; font-size: 0.75em;"
                                                onclick="showBayarModal({{ $item->id }}, '{{ $item->nama_plg }}', {{ $item->harga_paket }})">
                                                <img src="{{ asset('asset/img/icon/bayar.png') }}"
                                                    style="height: 30px; width: 30px;" alt="">
                                            </a>

                                            <a href="{{ route('pelanggan.historypembayaran', $item->id) }}"
                                                class="btn btn-info btn-sm">
                                                <img src="{{ asset('asset/img/icon/riwayat.png') }}"
                                                    style="height : 25px; width : 25px;" alt="">
                                            </a>
                                            <a href="{{ route('pelanggan.detail', $item->id) }}"
                                                class="btn btn-info btn-sm">
                                                <img src="{{ asset('asset/img/icon/user.png') }}"
                                                    style="height : 25px; width : 25px;" alt="">
                                            </a>
                                        </div>


                                        {{-- Modal Bayar --}}
                                        <div class="modal fade" id="bayarModal" tabindex="-1"
                                            aria-labelledby="bayarModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="bayarModalLabel">Pembayaran</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>

                                                    <form id="bayarForm" method="POST">
                                                        @csrf
                                                        @method('POST')

                                                        <input type="hidden" name="id" id="pelangganId">
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="tanggal_pembayaran" class="form-label">Untuk
                                                                    Pembayaran Bulan</label>
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
                                                                    <option value="tagihan">Tagihan</option>
                                                                    <option value="piutang">Piutang</option>
                                                                    <option value="PSB">PSB</option>
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

                                                            <div class="mb-3">
                                                                <p id="pembayaranDetails"></p>
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">Bayar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        @endforeach

                        {{-- DATA KARYAWAN --}}
                        @if (isset($karyawan) && $karyawan->count())
                            <hr>

                            @foreach ($karyawan as $index => $k)
                                <div class="card m-3">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            Karyawan #{{ $index + 1 }}
                                        </h5>
                                        <p class="card-text">
                                            <strong>Nama:</strong> {{ $k->nama }}<br>
                                            <strong>Posisi:</strong> {{ $k->posisi }}<br>
                                            <strong>No Telepon:</strong> {{ $k->no_telepon }}<br>
                                            <strong>Alamat:</strong> {{ $k->alamat }}<br>
                                            <strong>Gaji:</strong> Rp {{ number_format($k->gaji, 0, ',', '.') }}<br>
                                            <strong>Tanggal Gajian:</strong> {{ $k->tgl_gajihan }}
                                        </p>
                                        @if ($k->foto)
                                            <img src="{{ asset('storage/' . $k->foto) }}" class="img-thumbnail"
                                                style="max-height: 100px;">
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        {{-- DATA USER --}}
                        @if (isset($users) && $users->count())
                            <hr>
                            @foreach ($users as $index => $u)
                                <div class="card m-3">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            User #{{ $index + 1 }}
                                        </h5>
                                        <p class="card-text">
                                            <strong>Nama:</strong> {{ $u->name }}<br>
                                            <strong>Email:</strong> {{ $u->email }}<br>
                                            <strong>Role:</strong> {{ $u->role }}<br>
                                            <strong>Terakhir Login:</strong> {{ $u->last_login_at }}
                                        </p>
                                        @if ($u->foto)
                                            <img src="{{ asset('storage/' . $u->foto) }}" class="img-thumbnail"
                                                style="max-height: 100px;">
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        {{-- Tidak Ada Data Sama Sekali --}}
                        @if ($pelanggan->isEmpty() && $karyawan->isEmpty() && $users->isEmpty())
                            <p class="text-muted"><em>Tidak ada data ditemukan untuk pencarian "{{ $query }}"</em>
                            </p>
                        @endif

                    </div>
            @endif
        </div>
    </div>


@endsection


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

<script>
    document.getElementById("bayarForm").addEventListener("submit", function(event) {
        event.preventDefault(); // Mencegah form langsung submit

        var loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
        loadingModal.show(); // Tampilkan modal loading

        // Simulasi proses pembayaran (ganti dengan AJAX jika perlu)
        setTimeout(function() {
            loadingModal.hide(); // Sembunyikan modal loading

            // Simulasi sukses atau gagal (Gantilah dengan kondisi nyata dari server)
            var isSuccess = Math.random() > 0.3; // 70% sukses, 30% gagal

            if (isSuccess) {
                var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                document.getElementById('successMessage').innerText = "Pembayaran berhasil!";
                successModal.show();

                // Submit form setelah sukses (atau panggil API jika pakai AJAX)
                document.getElementById("bayarForm").submit();
            } else {
                var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                document.getElementById('errorMessage').innerText =
                    "Pembayaran gagal! Silakan coba lagi.";
                errorModal.show();
            }
        }, 3000); // Simulasi proses selama 3 detik
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));

        // Tampilkan modal loading terlebih dahulu
        loadingModal.show();

        // Tunggu sebentar sebelum menampilkan modal sukses atau error
        setTimeout(function() {
            loadingModal.hide(); // Sembunyikan modal loading

            @if (session('success'))
                document.getElementById("successMessage").innerText = "✅ {{ session('success') }}";
                var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();

                // Tutup modal sukses setelah 3 detik
                setTimeout(function() {
                    successModal.hide();
                }, 3000);
            @endif

            @if (session('error'))
                document.getElementById("errorMessage").innerText = "❌ {{ session('error') }}";
                var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                errorModal.show();

                // Tutup modal error setelah 3 detik
                setTimeout(function() {
                    errorModal.hide();
                }, 3000);
            @endif
        }, 1500); // Delay 1.5 detik untuk efek loading
    });
</script>
