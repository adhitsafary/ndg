@extends($layout)

@section('konten')
    <div class="card m-5">
        <h6 class="text text-center text-black mt-3"> Tambah Data rekap pemasangan</h6>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('rekap_pemasangan.store') }}" method="POST">
            @csrf
            <!-- Input ID Pelanggan -->
            <!--  <label for="id_plg" class=" mt-2">ID Pelanggan :</label>
                                                                                                                                            <input type="text" name="id_plg" required class="form-control"> -->

            <label for="nik" class=" mt-2">KTP :</label>
            <input type="text" name="nik" required class="form-control">

            <!-- Input keterangan -->
            <label for="nama" class=" mt-2">Nama :</label>
            <input type="text" name="nama" required class="form-control">

            <!-- Input jumlah -->
            <label for="alamat" class=" mt-2">Alamat :</label>
            <input type="text" name="alamat" required class="form-control">

            <!-- Input keterangan -->
            <label for="no_telpon" class=" mt-2">NO Telepon :</label>
            <input type="text" name="no_telpon" required class="form-control">


            <label for="" class=" mt-2">Paket</label>
            <select name="paket_plg" id="paket_plg" class="form-control" onchange="setHargaPaket()" required>
                <option value="" disabled selected>Pilih Paket</option>
                <option value="1">Paket 1 - Rp 125.000</option>
                <option value="2">Paket 2 - Rp 165.000</option>
                <option value="3">Paket 3 - Rp 205.000</option>
                <option value="4">Paket 4 - Rp 305.000</option>
                <option value="5">Paket 5 - Rp 120.000</option>
                <option value="6">Paket 6 - Rp 175.000</option>
                <option value="8">Paket 8 - Rp 100.000</option>
                <option value="9">Paket Tiara Net - Rp 99.000</option>
                <option value="7">Paket 100 Mbps - Rp 650.000</option>

                <option value="0">Paket 0 - Vocher</option>
            </select>
            <div class="invalid-feedback" id="paket_plgError">Field Paket tidak boleh kosong.</div>

            <label for="" class=" mt-2">Harga Paket</label>
            <input type="text" name="harga_paket" id="harga_paket" class="form-control " readonly required>

            <!-- Input keterangan -->
            <label for="tgl_pengajuan" class=" mt-2">Tanggal Pengajuan :</label>
            <input type="date" name="tgl_pengajuan" style="width: 150px" required class="form-control">

            <label for="tgl_aktivasi" class=" mt-2">Tanggal Aktivasi :</label>
            <input type="date" name="tgl_aktivasi" style="width: 150px" required class="form-control">

            <!-- Input jumlah -->
            <label for="registrasi" class=" mt-2">Registrasi :</label>
            <input type="number" name="registrasi" class="form-control" required>

            <br>

            <label for="sn_modem" class="">Modem :</label>
            <div class=" d-flex justify-content-start ">
                <button onclick="$('#sn_modem').select2()">Cari Modem</button>
            </div>
            <select name="sn_modem" id="sn_modem" class="form-control" style="width: 100%;">
                <option value="">Pilih Modem</option>
                @foreach ($modems as $modem)
                    <option value="{{ $modem->sn_modem }}">{{ $modem->sn_modem }} - {{ $modem->model }}</option>
                @endforeach
            </select>

            <br>
            <br>

            <label for="teknisi">Pilih Teknisi</label>
            <div>
                @csrf
                @foreach ($teknisi as $tech)
                    <input type="checkbox" name="teknisi[]" value="{{ $tech->nama }}"> {{ $tech->nama }}<br>
                @endforeach
            </div>


            <br>


            @csrf
            <label for="">Pilih Lokasi ODP</label>

            <select id="kecamatan" name="odp[]" class="form-control" required>
                <option value="">Pilih Kecamatan</option>
                @foreach ($odps->unique('kecamatan') as $odp)
                    <option value="{{ $odp->kecamatan }}">{{ $odp->kecamatan }}</option>
                @endforeach
            </select>

            <select id="desa" name="odp[]" class="form-control" disabled required>
                <option value="">Pilih Desa</option>
            </select>

            <select id="dusun" name="odp[]" class="form-control" disabled required>
                <option value="">Pilih Dusun</option>
            </select>

            <select id="kode_odp" name="odp[]" class="form-control" disabled required>
                <option value="">Pilih Kode ODP</option>
            </select>

            <select id="no_urut_odp" name="odp[]" class="form-control" disabled required>
                <option value="">Pilih No Urut ODP</option>
            </select>

            <p id="jumlah_port"></p>



            <!-- Input keterangan -->
            <label for="marketing" class=" mt-2">Marketing :</label>
            <input type="text" name="marketing" class="form-control" required>

            <label for="maps" class=" mt-2">Maps :</label>
            <input type="text" name="maps" class="form-control"  placeholder="Opsional, bisa tidak di isi">



            <!-- Input ID Keterangan -->
            <label for="longitude" class=" mt-2">longitude :</label>
            <input type="text" name="longitude" class="form-control" placeholder="Opsional, bisa tidak di isi">

            <!-- Input ID latitude -->
            <label for="latitude" class=" mt-2">latitude :</label>
            <input type="text" name="latitude" class="form-control"  placeholder="Opsional, bisa tidak di isi">


            <!-- Input ID Keterangan -->
            <label for="keterangan_plg" class=" mt-2"> Keterangan :</label>
            <input type="text" name="keterangan_plg" class="form-control"  placeholder="Opsional, bisa tidak di isi"> <br>


            <label for="cabang" class=" mt-2"> Pilih Branch / Cabang</label>
            <select name="cabang" class="form-control" required>
                <option value="">-- Pilih Cabang --</option>
                @foreach ($branch_cabang as $cabang)
                    <option value="{{ $cabang->kode_cabang }}">{{ $cabang->nama_cabang }}</option>
                @endforeach
            </select>


            <label for="kt_plg" class=" mt-2"> Kategori Pelanggan : </label>
            <select name="kt_plg" class="form-control" required>
                <option value="">-- Pilih Kategori Pelanggan --</option>
                <option value="Prabayar">Prabayar / Bayar dulu baru Pake</option>
                <option value="Pascabayar ">Pascabayar / Pake dulu baru bayar</option>
            </select>

            <label for="token_id" class=" mt-2"> Pilih Nomer untuk Bot PSB:</label>
            <select name="token_id" class="form-control" required>
                <option value="">-- Pilih Nomer --</option>
                @foreach ($botTokens as $token)
                    <option value="{{ $token->id }}">{{ $token->name }}</option>
                @endforeach
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
            <!-- Submit button -->
            <button type="submit" class="btn btn-primary btn-sm">Simpan</button> <br><br>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function setHargaPaket() {
            const paketPlg = document.getElementById('paket_plg').value;
            const hargaPaket = document.getElementById('harga_paket');

            const hargaList = {
                '1': '125000',
                '2': '165000',
                '3': '205000',
                '4': '305000',
                '5': '120000',
                '6': '175000',
                '7': '650000',
                '8': '100000',
                '9': '99000',
                '0': '0',
            };

            // Set harga sesuai pilihan paket
            if (paketPlg in hargaList) {
                hargaPaket.value = hargaList[paketPlg];
            } else {
                hargaPaket.value = ''; // Kosongkan jika tidak ada paket yang dipilih
            }
        };
    </script>

    <script>
        $(document).ready(function() {
            console.log("Select2 script loaded"); // Debugging untuk cek apakah script berjalan

            $('#sn_modem').select2({
                placeholder: "Cari dan Pilih Modem",
                allowClear: true
            }).on('select2:open', function() {
                console.log("Dropdown terbuka"); // Debugging saat dropdown dibuka
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


    <script>
        let odpData = @json($odps);

        $(document).ready(function() {
            $('#kecamatan').change(function() {
                let kecamatan = $(this).val();
                let desaOptions = odpData.filter(odp => odp.kecamatan === kecamatan).map(odp => odp.desa);
                desaOptions = [...new Set(desaOptions)];
                updateDropdown('#desa', desaOptions);
            });

            $('#desa').change(function() {
                let desa = $(this).val();
                let dusunOptions = odpData.filter(odp => odp.desa === desa).map(odp => odp.dusun);
                dusunOptions = [...new Set(dusunOptions)];
                updateDropdown('#dusun', dusunOptions);
            });

            $('#dusun').change(function() {
                let dusun = $(this).val();
                let kodeOdpOptions = odpData.filter(odp => odp.dusun === dusun).map(odp => odp.kode_odp);
                kodeOdpOptions = [...new Set(kodeOdpOptions)];
                updateDropdown('#kode_odp', kodeOdpOptions);
            });

            $('#kode_odp').change(function() {
                let kodeOdp = $(this).val();
                let noUrutOptions = odpData.filter(odp => odp.kode_odp === kodeOdp).map(odp => odp
                    .no_urut_odp);
                let jumlahPort = odpData.find(odp => odp.kode_odp === kodeOdp)?.jml_port || '';
                $('#jumlah_port').text(`Jumlah Port: ${jumlahPort}`);
                updateDropdown('#no_urut_odp', noUrutOptions);
            });
        });

        function updateDropdown(selector, options) {
            let dropdown = $(selector);
            dropdown.empty().append('<option value="">Pilih</option>');
            options.forEach(option => dropdown.append(`<option value="${option}">${option}</option>`));
            dropdown.prop('disabled', options.length === 0);
        }
    </script>

    <script>
        $('form').submit(function() {
            $('#desa, #dusun, #kode_odp, #no_urut_odp').prop('disabled', false);
        });
    </script>





@endsection
