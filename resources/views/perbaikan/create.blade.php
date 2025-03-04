@extends($layout)

@section('konten')
    <div class="card m-5">
        <h6 class="text-center text-black mt-3">Tambah Data Perbaikan</h6>
        <form id="perbaikanForm" action="{{ route('perbaikan.store') }}" method="POST">
            @csrf

            <label for="nama_plg">Cari Nama Pelanggan</label>
            <select id="nama_plg_select" name="nama_plg_select" class="form-control mt-2"></select> <br><br>

            <!-- Input tersembunyi untuk nama pelanggan -->
            <input type="hidden" id="nama_plg" name="nama_plg">

            <label for="id_plg">ID Pelanggan</label>
            <input type="text" id="id_plg" name="id_plg" class="form-control mt-2" readonly>

            <label for="alamat_plg">Alamat</label>
            <input type="text" id="alamat_plg" name="alamat_plg" class="form-control mt-2" readonly>

            <label for="no_telepon_plg">No Telpon</label>
            <input type="text" id="no_telepon_plg" name="no_telepon_plg" class="form-control mt-2" readonly>

            <label for="paket_plg">Paket</label>
            <input type="text" id="paket_plg" name="paket_plg" class="form-control mt-2" readonly>

            <label for="odp">ODP</label>
            <input type="text" id="odp" name="odp" class="form-control mt-2">
            <div class="invalid-feedback" id="odpError">Field ODP tidak boleh kosong, bila tidak ada tulis " 0 ".</div>

            <label for="maps">Maps</label>
            <input type="text" id="maps" name="maps" class="form-control mt-2">

            <div class="form-group">
                <label for="teknisi">Pilih Teknisi</label>
                <div class="mt-2">
                    <input type="checkbox" name="teknisi[]" value="Deden"> Deden<br>
                    <input type="checkbox" name="teknisi[]" value="Agisdut"> Agisdut<br>
                    <input type="checkbox" name="teknisi[]" value="Dindin"> Dindin<br>
                    <input type="checkbox" name="teknisi[]" value="Mursidi"> Mursidi<br>
                    <input type="checkbox" name="teknisi[]" value="Isep"> Isep<br>
                    <input type="checkbox" name="teknisi[]" value="Indra"> Indra<br>
                    <input type="checkbox" name="teknisi[]" value="Adit"> Adit<br>
                    <input type="checkbox" name="teknisi[]" value="Johan"> Johan<br>
                    <input type="checkbox" name="teknisi[]" value="Gilang"> Gilang<br>
                </div>
            </div>

            <div class="form-group">
                <label for="keterangan">Gangguan</label>
                <select id="keterangan" name="keterangan" class="form-control mt-2">
                    <option value="">Pilih Gangguan</option>
                    <option value="Modem error / matot">Modem error / matot</option>
                    <option value="Los / modem merah">Los / modem merah</option>
                    <option value="Ganti Nama Wifi / Password">Ganti Nama / Password</option>
                </select>
                <div class="invalid-feedback" id="keteranganError">Field Gangguan tidak boleh kosong, bila tidak ada tulis
                    "0".</div>
            </div>

            <div class="mb-4">
                <label for="maps">Keterangan</label>
                <input type="text" id="info" name="info" class="form-control mb-2">
            </div>

            <button type="submit" class="btn btn-primary btn-sm">Simpan</button> <br> <br>
        </form>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#nama_plg_select').select2({
                    placeholder: 'Cari nama pelanggan',
                    ajax: {
                        url: '/search-pelanggan',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                search: params.term
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data
                            };
                        },
                        cache: true
                    }
                });

                $('#nama_plg_select').on('select2:select', function(e) {
                    var data = e.params.data;

                    $.ajax({
                        url: '/get-pelanggan/' + data.id,
                        type: 'GET',
                        dataType: 'json',
                        success: function(pelanggan) {
                            if (pelanggan) {
                                $('#id_plg').val(pelanggan.id_plg);
                                $('#alamat_plg').val(pelanggan.alamat_plg);
                                $('#no_telepon_plg').val(pelanggan.no_telepon_plg);
                                $('#paket_plg').val(pelanggan.paket_plg);
                                $('#odp').val(pelanggan.odp);
                                $('#maps').val(pelanggan.maps);
                                $('#nama_plg').val(data.text);
                            } else {
                                alert('Data pelanggan tidak ditemukan.');
                            }
                        },
                        error: function() {
                            alert('Terjadi kesalahan saat mengambil data pelanggan.');
                        }
                    });
                });
            });
        </script>
    @endsection
