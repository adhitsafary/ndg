@extends($layout)

@section('konten')
    <div class="container mt-4 card">
        <h6 class="text text-center text-black mt-3"> Edit Data rekap pemasangan : {{ $rekap_pemasangan->nama }} </h6>
        <form action="{{ route('rekap_pemasangan.update', $rekap_pemasangan->id) }}" method="POST">


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
                            <p class="mt-3">Sedang Memproses Data...</p>
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
                            <h3 class="text-success">✔</h3>
                            <p id="successMessage" class="mt-2"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Error -->
            <div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">
                                <span class="me-2">❌</span> Gagal!
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <h3 class="text-danger">✖</h3>
                            <p id="errorMessage" class="mt-2"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>

            @csrf

            <label for="">ID Pelanggan</label>
            <input type="text" name="id_plg" value="{{ $rekap_pemasangan->id_plg }}" class="form-control mb-3">

            <label for="">NIK</label>
            <input type="text" name="nik" value="{{ $rekap_pemasangan->nik }}" class="form-control mb-3">

            <label for="">Nama</label>
            <input type="text" name="nama" value="{{ $rekap_pemasangan->nama }}" class="form-control mb-3">

            <label for="">Alamat</label>
            <input type="text" name="alamat" value="{{ $rekap_pemasangan->alamat }}" class="form-control mb-3">

            <label for="">No Telepon</label>
            <input type="text" name="no_telpon" value="{{ $rekap_pemasangan->no_telpon }}" class="form-control mb-3">

            <label for="">Aktivasi</label>
            <input type="date" name="tgl_aktivasi" value="{{ $rekap_pemasangan->tgl_aktivasi }}"
                class="form-control mb-3">

            <label for="">Paket</label>
            <input type="text" name="paket_plg" value="{{ $rekap_pemasangan->paket_plg }}"
                class="form-control mb-3">

            <label for="">Harga Paket</label>
            <input type="text" name="harga_paket" value="{{ $rekap_pemasangan->harga_paket }}"
                class="form-control mb-3">

            <label for="">Jatuh Tempo</label>
            <input type="text" name="jt" value="{{ $rekap_pemasangan->jt }}" class="form-control mb-3">

            <label for="">Status</label>
            <input type="text" name="status" value="{{ $rekap_pemasangan->status }}" class="form-control mb-3">

            <label for="">Pengajuan</label>
            <input type="date" name="tgl_pengajuan" value="{{ $rekap_pemasangan->tgl_pengajuan }}"
                class="form-control mb-3">

            <label for="">Registrasi</label>
            <input type="text" name="registrasi" value="{{ $rekap_pemasangan->registrasi }}"
                class="form-control mb-3">

            <label for="">Marketing</label>
            <input type="text" name="marketing" value="{{ $rekap_pemasangan->marketing }}"
                class="form-control mb-3">

            <br>
            <label for="sn_modem_awal">SN Modem Awal</label>
            <input type="text" id="sn_modem_awal" name="sn_modem_awal" class="form-control"
                value="{{ $rekap_pemasangan->sn_modem }}" readonly>

            <label for="sn_modem_baru">SN Modem Baru</label>
            <input type="text" id="sn_modem_baru" name="sn_modem_baru" class="form-control"
                placeholder="Masukkan SN Modem Baru">

            <label for="sn_modem">Modem :</label>
            <select name="sn_modem" id="sn_modem" style="width: 100%;">
                <option value="" {{ $rekap_pemasangan->sn_modem == null ? 'selected' : '' }}>Tidak Pilih Modem
                </option>
                @foreach ($modems as $modem)
                    <option value="{{ $modem->sn_modem }}"
                        {{ $rekap_pemasangan->sn_modem == $modem->sn_modem ? 'selected' : '' }}>
                        {{ $modem->sn_modem }} - {{ $modem->model }}
                    </option>
                @endforeach
            </select>








            <div class="form-group mt-4">
                <label for="teknisi">Pilih Teknisi :</label>
                <div>
                    @php
                        $teknisiTerpilih = explode(',', $rekap_pemasangan->teknisi); // Konversi string ke array
                    @endphp
                    @php
                        // Pecah string menjadi array dan hilangkan spasi di awal & akhir setiap elemen
                        $teknisiTerpilih = array_map('trim', explode(',', $rekap_pemasangan->teknisi));
                    @endphp

                    @foreach (['Deden', 'Agisdut', 'Dindin', 'Mursidi', 'Isep', 'Indra', 'Adit', 'Johan', 'Gilang'] as $teknisi)
                        <input type="checkbox" name="teknisi[]" value="{{ $teknisi }}"
                            {{ in_array($teknisi, $teknisiTerpilih) ? 'checked' : '' }}>
                        {{ $teknisi }}<br>
                    @endforeach

                </div>
            </div>

            <button class="btn btn-primary btn-sm">Simpan</button> <br><br>
        </form>
    </div>
    //

    <script>
        $(document).ready(function() {
            $('#sn_modem').select2({
                placeholder: "Cari dan Pilih Modem",
                allowClear: true
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Inisialisasi Select2
            $('#sn_modem').select2({
                placeholder: "Cari dan Pilih Modem",
                allowClear: true
            });

            // Update SN Modem Awal ketika pilihan berubah
            $('#sn_modem').on('change', function() {
                let selectedModem = $(this).val();
                $('#sn_modem_awal').val(selectedModem ? selectedModem : ''); // Kosongkan jika tidak pilih
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let snModemBaruInput = document.getElementById("sn_modem_baru");
            let snModemSelect = document.getElementById("sn_modem");

            // Saat dropdown berubah, isi input SN Modem Baru
            snModemSelect.addEventListener("change", function() {
                snModemBaruInput.value = this.value;
            });

            // Saat input SN Modem Baru diketik manual, cek apakah ada di daftar
            snModemBaruInput.addEventListener("input", function() {
                let inputValue = this.value;
                let found = false;

                for (let option of snModemSelect.options) {
                    if (option.value === inputValue) {
                        found = true;
                        snModemSelect.value = inputValue; // Pilih di dropdown jika cocok
                        break;
                    }
                }

                if (!found) {
                    snModemSelect.value = ""; // Kosongkan dropdown jika tidak ada di daftar
                }
            });
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

                    // Tutup modal sukses setelah 3 d   etik
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
@endsection
