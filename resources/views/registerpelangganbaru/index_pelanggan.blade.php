@extends('layout_daptar')

@section('konten')
    <br><br>
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center">
                <h4 class="mb-0">Form Pendaftaran Net Digital Group</h4>
            </div>



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
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="successModalLabel">
                                <span class="me-2">✅</span> Pendftaran Berhasil!
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
                                <span class="me-2">❌</span> Pendftaran Gagal!
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



            <div class="card-body">
                <form action="{{ route('registerpelangganbaru.store') }}" method="POST">
                    @csrf

                    <div class="row "> 
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>NIK</label>
                                <input type="text" class="form-control" name="nik_plg" required>
                                <div class="invalid-feedback">Harap isi NIK.</div>
                            </div>
                            <div class="form-group mb-3">
                                <label>Nama</label>
                                <input type="text" class="form-control" name="nama_plg" required>
                                <div class="invalid-feedback">Harap isi Nama.</div>
                            </div>
                            <div class="form-group mb-3">
                                <label>No. Telepon</label>
                                <input type="text" class="form-control" name="no_tlp_plg" required>
                                <div class="invalid-feedback">Harap isi No. Telepon.</div>
                            </div>
                            <div class="form-group mb-3">
                                <label>Email</label>
                                <input type="email" class="form-control" name="email_plg" required>
                                <div class="invalid-feedback">Harap isi Email yang valid.</div>
                            </div>
                            <label>Pilih Paket Internet</label>
                            <select name="paket_plg" class="form-control" required>
                                <option value="" disabled selected>Pilih Paket</option>
                                <option value="125.000">Paket 1 - Rp 125.000</option>
                                <option value="165.000">Paket 2 - Rp 165.000</option>
                                <option value="205.000">Paket 3 - Rp 205.000</option>
                                <option value="305.000">Paket 4 - Rp 305.000</option>

                                <option value="650.000">Paket 100 Mbps - Rp 650.000</option>
                                <option value="0">Paket Vocheran</option>
                            </select>
                            <div class="invalid-feedback">Harap pilih paket internet.</div>
                            <br>
                        </div>

                        <div class="col-md-6">

                            <div class="form-group mb-3">
                                <label>Provinsi</label>
                                <select id="provinsi" class="form-control" name="provinsi" required></select>
                                <div class="invalid-feedback">Harap pilih Provinsi.</div>
                            </div>
                            <div class="form-group mb-3">
                                <label>Kabupaten</label>
                                <select id="kabupaten" class="form-control" name="kabupaten" required></select>
                                <div class="invalid-feedback">Harap pilih Kabupaten.</div>
                            </div>
                            <div class="form-group mb-3">
                                <label>Kecamatan</label>
                                <select id="kecamatan" class="form-control" name="kecamatan" required></select>
                                <div class="invalid-feedback">Harap pilih Kecamatan.</div>
                            </div>
                            <div class="form-group mb-3">
                                <label>Desa</label>
                                <select id="desa" class="form-control" name="desa" required></select>
                                <div class="invalid-feedback">Harap pilih Desa.</div>
                            </div>

                            <div class="form-group mb-3">
                                <label>Alamat Lengkap (Blok/RT/RW)</label>
                                <input type="text" class="form-control" name="alamat_plg" required>
                                <div class="invalid-feedback">Harap isi Alamat Lengkap.</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-center mt-3">
                        <input type="checkbox" id="termsCheckbox" required>
                        <label for="termsCheckbox">
                            Saya menyetujui <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">syarat
                                & ketentuan</a>.
                        </label>
                    </div>

                    <!-- Modal Syarat & Ketentuan -->
                    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="termsModalLabel">Syarat & Ketentuan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>1.</strong> Paket 1, 2, 3, 4, dikenakan biaya Pemasangan <strong>Rp.300.000</strong></p>
                                    <p><strong>2.</strong> Ketentuan Berlangganan Minimal <strong>6 Bulan</strong></p>
                                    <p><strong>3.</strong> Perangkat Modem merupakan milik <strong>PT HAYAT TEKNOLOGI INFORMATIKA,</strong>
                                        dan tidak bisa di
                                        perjual belikan dan apabila kontrak berakhir wajib dikembalikan</p>
                                    <p><strong>4.</strong> Apabila terjadi keterlambatan pembayaran iuran berlanggan wifi akan dikenakan
                                        <strong>Sanksi berupa pemutusan sementara jaringan wifi</strong>
                                    </p>
                                    <p><strong>5.</strong> Pelayanan Pengaduan gangguan layanan wifi akan di proses / dilakukan perbaikan, 1
                                        hari setelah laporan di terima oleh Admin 1x24 jam setelah laporan di terima. </p>
                                    <p><strong>6.</strong> Ketentuan dan peraturan dapat berubah sewaktu waktu tanpa pemberitahuan
                                        sebelumnya, oleh pihak Net Digital Group. </p>
                                    <p><strong>Dengan mendaftar, Anda menyetujui syarat & ketentuan ini.</strong></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Daptar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <br><br> <br>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var checkbox = document.getElementById("termsCheckbox");
            var submitButton = document.getElementById("submitButton");

            // Set tombol submit disable saat pertama kali halaman dimuat
            submitButton.disabled = true;

            checkbox.addEventListener("change", function() {
                submitButton.disabled = !this.checked;
            });
        });
    </script>


    <script>
        document.querySelector("form").addEventListener("submit", function(event) {
            if (!this.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            } else {
                var loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
                loadingModal.show();
            }
            this.classList.add("was-validated");
        });

        function loadDropdown(url, elementId) {
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    let select = document.getElementById(elementId);
                    select.innerHTML = "<option value='' disabled selected>Pilih</option>";
                    data.forEach(item => {
                        select.innerHTML += `<option value="${item.id}">${item.name}</option>`;
                    });
                })
                .catch(error => console.error(`Error fetching ${elementId}:`, error)); // Debugging
        }

        document.addEventListener("DOMContentLoaded", function() {
            function loadDropdown(url, elementId) {
                fetch(url)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        let select = document.getElementById(elementId);
                        select.innerHTML = "<option value='' disabled selected>Pilih</option>";
                        data.forEach(item => {
                            select.innerHTML += `<option value="${item.id}">${item.name}</option>`;
                        });
                        console.log(`Data ${elementId} berhasil dimuat`, data); // Debugging
                    })
                    .catch(error => console.error(`Gagal memuat ${elementId}:`, error)); // Debugging
            }

            loadDropdown("https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json", "provinsi");

            document.getElementById("provinsi").addEventListener("change", function() {
                loadDropdown(
                    `https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${this.value}.json`,
                    "kabupaten");
            });

            document.getElementById("kabupaten").addEventListener("change", function() {
                loadDropdown(
                    `https://www.emsifa.com/api-wilayah-indonesia/api/districts/${this.value}.json`,
                    "kecamatan");
            });

            document.getElementById("kecamatan").addEventListener("change", function() {
                loadDropdown(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${this.value}.json`,
                    "desa");
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

                    // Tutup modal sukses setelah 3 detik
                    setTimeout(function() {
                        successModal.hide();
                    }, 30000);
                @endif

                @if (session('error'))
                    document.getElementById("errorMessage").innerText = "❌ {{ session('error') }}";
                    var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                    errorModal.show();

                    // Tutup modal error setelah 3 detik
                    setTimeout(function() {
                        errorModal.hide();
                    }, 30000);
                @endif
            }, 1500); // Delay 1.5 detik untuk efek loading
        });
    </script>
@endsection
