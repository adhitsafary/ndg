@extends($layout)

@section('konten')
    <div class="card m-5">
        <h5 class="text-center text-black mb-3 font-weight-bold " style="color: black">Tambah Data Pemasangan Baru</h5>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('wo.store') }}" method="POST" class="font font-weight-bold" style="color: black">
            @csrf



            <label for="id_plg">ID Pelanggan</label>
            <input type="text" id="id_plg" name="id_plg" class="form-control mb-2">

            <label for="id_plg">Nama</label>
            <input type="text" id="nama_plg" name="nama_plg" class="form-control mb-2">
            <label for="alamat_plg">Alamat</label>
            <input type="text" id="alamat_plg" name="alamat_plg" class="form-control mb-2">

            <label for="no_telepon_plg">No Telpon</label>
            <input type="text" id="no_telepon_plg" name="no_telepon_plg" class="form-control mb-2">

            <label for="paket_plg">Paket</label>
            <input type="text" id="paket_plg" name="paket_plg" class="form-control mb-2">

            <label for="odp">ODP</label>
            <input type="text" id="odp" name="odp" class="form-control mb-2">
            <div class="invalid-feedback" id="odpError">Field ODP tidak boleh kosong, bila tidak ada tulis " 0 ".</div>

            <label for="maps">Maps</label>
            <input type="text" id="maps" name="maps" class="form-control mb-2">
            <div class="invalid-feedback" id="mapsError">Field Maps tidak boleh kosong, bila tidak ada tulis " 0 ".</div>

            <label for="teknisi">Pilih Teknisi</label>
            <div>
                @foreach ($teknisi as $tech)
                    <input type="checkbox" name="teknisi[]" value="{{ $tech->nama }}"> {{ $tech->nama }}<br>
                @endforeach
            </div>

            <br>

            <label for="keterangan">Gangguan</label>
            <select id="keterangan" name="keterangan" class="form-control">
                <option value="">Pilih Gangguan</option>
                <option value="Modem error">Modem error</option>
                <option value="Modem matot">Modem matot</option>
                <option value="Ganti Adaptor">Ganti Adaptor</option>
                <option value="Los / modem merah">Los / modem merah</option>
                <option value="Tidak Ada Jaringan">Tidak Ada Jaringan</option>
                <option value="Ganti Nama Wifi / Password">Ganti Nama / Password</option>

                <option value="Lain-Lain">Lain-Lain</option>
            </select>

            <div class="mt-4">
                <label for="inventory">Barang yang Digunakan</label>
                <div class="form-group">
                    <select id="barangSelect" class="form-control">
                        <option value="">-- Pilih Barang --</option>
                        @foreach ($inventory as $item)
                            <option value="{{ $item->nm_brg }}" data-harga="{{ $item->harga_satuan }}"
                                data-stok="{{ $item->jml_brg }}">
                                {{ $item->nm_brg }} (Stok: {{ $item->jml_brg }} {{ $item->satuan }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="row mt-3" id="barangTerpilih">
                    <!-- Kartu barang akan ditambahkan di sini -->
                </div>

                <div class="mt-2 text-left">
                    <h5>Total Harga: <span id="totalHarga">Rp 0</span></h5>
                </div>
            </div>





            <br>
            <button type="submit" class="btn btn-primary btn-sm">Simpan</button> <br><br><br><br>
        </form>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
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

        <script>
            $(document).ready(function() {
                // Validasi jumlah barang tidak boleh lebih dari stok tersedia
                $('.inventory-input').on('input', function() {
                    var maxVal = $(this).data('max');
                    var currentVal = $(this).val();

                    if (parseInt(currentVal) > parseInt(maxVal)) {
                        alert('Jumlah barang melebihi stok yang tersedia!');
                        $(this).val(maxVal);
                    }
                });
            });
        </script>


        <script>
            $(document).ready(function() {
                function updateTotalHarga() {
                    let totalHarga = 0;
                    $('.inventory-input').each(function() {
                        let jumlah = parseInt($(this).val()) || 0;
                        let harga = parseInt($(this).closest('.card-body').find('.harga-barang').data(
                            'harga')) || 0;
                        totalHarga += jumlah * harga;
                    });
                    $('#totalHarga').text('Rp ' + totalHarga.toLocaleString());
                }

                $('#barangSelect').change(function() {
                    var namaBarang = $(this).val();
                    var harga = $(this).find(':selected').data('harga');
                    var stok = $(this).find(':selected').data('stok');

                    if (namaBarang) {
                        if ($('#barang_' + namaBarang.replace(/\s+/g, '_')).length === 0) {
                            var card = `
                        <div class="col-md-4 mb-3" id="barang_${namaBarang.replace(/\s+/g, '_')}">
                            <div class="card border-primary">
                                <div class="card-body">
                                    <h5 class="card-title">${namaBarang}</h5>
                                    <p class="card-text harga-barang" data-harga="${harga}"><strong>Harga:</strong> Rp ${harga.toLocaleString()}</p>
                                    <p class="card-text"><strong>Stok:</strong> ${stok}</p>
                                    <label>Jumlah:</label>
                                    <input type="number" class="form-control inventory-input"
                                           name="inventory[${namaBarang}][jml_brg]"
                                           min="1" max="${stok}" value="1" data-max="${stok}">
                                    <input type="hidden" name="inventory[${namaBarang}][harga_satuan]" value="${harga}">
                                    <button type="button" class="btn btn-danger btn-sm mt-2 remove-barang">Hapus</button>
                                </div>
                            </div>
                        </div>
                        `;

                            $('#barangTerpilih').append(card);
                            updateTotalHarga();
                        } else {
                            alert('Barang sudah ditambahkan!');
                        }
                    }
                });

                $(document).on('input', '.inventory-input', function() {
                    let maxVal = $(this).data('max');
                    let jumlah = parseInt($(this).val()) || 1;
                    if (jumlah > maxVal) {
                        alert('Jumlah barang melebihi stok!');
                        $(this).val(maxVal);
                    }
                    updateTotalHarga();
                });

                $(document).on('click', '.remove-barang', function() {
                    $(this).closest('.col-md-4').remove();
                    updateTotalHarga();
                });
            });
        </script>
    @endsection
