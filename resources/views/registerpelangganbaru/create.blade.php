@extends($layout)

@section('konten')
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center">
                <h4 class="mb-0">Form Pendaftaran Wifi</h4>
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
                                <option value="120.000">Paket 5 - Rp 120.000</option>
                                <option value="175.000">Paket 6 - Rp 175.000</option>
                                <option value="650.000">Paket 100 Mbps - Rp 650.000</option>
                                <option value="0">Paket Vocheran</option>
                            </select>
                            <div class="invalid-feedback">Harap pilih paket internet.</div>
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
                    <div class="text-center">
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
@endsection
