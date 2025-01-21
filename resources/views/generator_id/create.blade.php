@extends($layout)

@section('konten')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>Buat Generator ID Baru</h1>
                <form action="{{ route('generator_id.store') }}" method="POST">
                    @csrf


                    <label for="nama_plg">Cari Nama Pelanggan</label>
                    <select id="nama_plg_select" name="nama_plg_select" class="form-control mt-2"></select> <br><br>

                    <div class="form-group">
                        <label for="kode_perusahaan">Kode Perusahaan</label>
                        <input type="text" name="kode_perusahaan" class="form-control" required maxlength="100">
                    </div>
                    <div class="form-group">
                        <label for="kode_nik">NIK</label>
                        <input type="text" name="kode_nik" class="form-control" required maxlength="100">
                    </div>
                    <div class="form-group">
                        <label for="id_plg">ID Pelanggan</label>
                        <input type="text" id="id_plg" name="id_plg" class="form-control" required maxlength="100">
                    </div>
                    <div class="form-group">
                        <label for="nama_plg">Nama Pelanggan</label>
                        <input type="text" id="nama_plg" name="nama_plg" class="form-control" required maxlength="100">
                    </div>
                    <div class="form-group">
                        <label for="kode_odp">Kode ODP</label>
                        <input type="text" id="kode_odp" name="kode_odp" class="form-control" required maxlength="100">
                    </div>
                    <div class="form-group">
                        <label for="kode_paket_plg">Kode Paket</label>
                        <input type="text" id="kode_paket_plg" name="kode_paket_plg" class="form-control" required
                            maxlength="100">
                    </div>



                    <button type="submit" class="btn btn-success">Save</button>
                </form>
            </div>
        </div>
    </div>


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
                        if (pelanggan) { // Pastikan data pelanggan tidak kosong
                            $('#id').val(pelanggan.id);
                            $('#id_plg').val(pelanggan.id_plg);
                            $('#kode_paket_plg').val(pelanggan.paket_plg);
                            $('#kode_odp').val(pelanggan.odp);
                            $('#nama_plg').val(data
                                .text); // Simpan nama pelanggan ke input tersembunyi
                        } else {
                            // Tampilkan pesan kesalahan jika tidak ada data pelanggan
                            alert('Data pelanggan tidak ditemukan.');
                        }
                    },
                    error: function() {
                        alert('Terjadi kesalahan saat mengambil data pelanggan.');
                    }
                });
            });

            $('#perbaikanForm').on('submit', function(e) {
                var isValid = true;



                // Validasi field odp
                var odp = $('#odp').val();
                if (!odp) { // Ubah dari === "" ke !odp untuk memeriksa kebenaran
                    $('#odp').addClass('is-invalid');
                    $('#odpError').show();
                    isValid = false;
                } else {
                    $('#odp').removeClass('is-invalid');
                    $('#odpError').hide();
                }



                if (!isValid) {
                    e.preventDefault(); // Mencegah pengiriman form jika tidak valid
                }
            });
        });
    </script>
@endsection
