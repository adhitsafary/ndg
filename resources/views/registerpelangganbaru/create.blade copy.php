@extends($layout)

@section('konten')
<div class=" ml-5 mr-5 mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center">
                <h4 class="mb-0">Form Pendapataran Wifi</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('registerpelangganbaru.store') }}" method="POST">
                    @csrf

                    <div class="form-group mb-3">
                        <label>NIK</label>
                        <input type="text" class="form-control" name="nik_plg" placeholder="NIK" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Nama</label>
                        <input type="text" class="form-control" name="nama_plg" placeholder="Nama" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>No. Telepon</label>
                        <input type="text" class="form-control" name="no_tlp_plg" placeholder="No. Telepon" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email_plg" placeholder="Email" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Paket</label>
                        <input type="text" class="form-control" name="paket_plg" placeholder="Paket" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Alamat Lengkap</label>
                        <input type="text" class="form-control" name="alamat_plg" placeholder="Alamat" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Provinsi</label>
                        <select id="provinsi" class="form-control" name="provinsi"></select>
                    </div>
                    <div class="form-group mb-3">
                        <label>Kabupaten</label>
                        <select id="kabupaten" class="form-control" name="kabupaten"></select>
                    </div>
                    <div class="form-group mb-3">
                        <label>Kecamatan</label>
                        <select id="kecamatan" class="form-control" name="kecamatan"></select>
                    </div>
                    <div class="form-group mb-3">
                        <label>Desa</label>
                        <select id="desa" class="form-control" name="desa"></select>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function loadDropdown(url, elementId) {
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        let select = document.getElementById(elementId);
                        select.innerHTML = "<option>Pilih</option>";
                        data.forEach(item => {
                            select.innerHTML += `<option value="${item.id}">${item.name}</option>`;
                        });
                    });
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
