@extends($layout)
@section('konten')
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
                        <span class="visually-hidden">Loading...</span>
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
                    @php
                        $listTeknisi = [
                            'Deden',
                            'Agisdut',
                            'Dindin',
                            'Mursidi',
                            'Isep',
                            'Indra',
                            'Adit',
                            'Johan',
                            'Gilang',
                        ];
                    @endphp

                    @foreach ($listTeknisi as $teknisi)
                        <input type="checkbox" name="teknisi[]" value="{{ $teknisi }}"
                            {{ in_array($teknisi, $user_x100c) ? 'checked' : '' }}>
                        {{ $teknisi }}<br>
                    @endforeach
                </div>
            </div>


            <div class="form-group">
                <label for="keterangan">Gangguan</label>
                <select id="keterangan" name="keterangan" class="form-control mt-2">
                    <option value="">Pilih Gangguan</option>
                    <option value="Modem error / matot">Modem error / matot</option>
                    <option value="Los / modem merah">Los / modem merah</option>
                    <option value="Ganti Nama Wifi / Password">Ganti Nama / Password</option>
                    <option value="Lain-Lain">Lain-Lain</option>
                </select>
                <div class="invalid-feedback" id="keteranganError">Field Gangguan tidak boleh kosong, bila tidak ada tulis
                    "0".</div>
            </div>

            <div class="form-group">
                <label for="inventory_id">Pilih Inventory</label>
                <select id="inventory_id" class="form-control mt-2">
                    <option value="">Pilih Barang</option>
                    @foreach ($inventories as $inventory)
                        <option value="{{ $inventory->id }}" data-harga="{{ $inventory->harga_satuan }}"
                            data-jumlah="{{ $inventory->jml_brg }}">
                            {{ $inventory->nm_brg }} (Tersedia: {{ $inventory->jml_brg }})
                        </option>
                    @endforeach
                </select>
            </div>
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Harga Satuan</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="inventory_list">
                </tbody>
            </table>


            <!--  <div class="form-group">
                                            <label for="jml_brg">Jumlah Digunakan</label>
                                            <input type="number" id="jml_brg" name="jml_brg" class="form-control mt-2" min="1"
                                                required>
                                        </div> -->

            <div class="form-group">
                <label for="harga_total">Total Harga</label>
                <input type="text" id="harga_total" name="harga_total" class="form-control mt-2" readonly>
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


            $(document).ready(function() {
                $('#inventory_id').on('change', function() {
                    let selectedOption = $(this).find('option:selected');
                    let inventoryId = selectedOption.val();
                    let namaBarang = selectedOption.text();
                    let hargaSatuan = parseFloat(selectedOption.data('harga')) || 0;
                    let jumlahTersedia = parseInt(selectedOption.data('jumlah')) || 0;

                    if (inventoryId && $('#row-' + inventoryId).length === 0) {
                        let newRow = `
                <tr id="row-${inventoryId}">
                    <td>${namaBarang}</td>
                    <td>
                        <input type="number" name="jumlah[${inventoryId}]" class="form-control jml_brg" min="1" max="${jumlahTersedia}" data-harga="${hargaSatuan}" required>
                    </td>
                    <td>${hargaSatuan}</td>
                    <td class="total-harga">0</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-row" data-id="${inventoryId}">Hapus</button>
                    </td>
                </tr>
            `;
                        $('#inventory_list').append(newRow);
                    }
                });

                $(document).on('input', '.jml_brg', function() {
                    let jumlah = parseInt($(this).val()) || 0;
                    let hargaSatuan = parseFloat($(this).data('harga')) || 0;
                    let totalHarga = jumlah * hargaSatuan;
                    $(this).closest('tr').find('.total-harga').text(totalHarga);
                });

                $(document).on('click', '.remove-row', function() {
                    let inventoryId = $(this).data('id');
                    $('#row-' + inventoryId).remove();
                });
            });
        </script>


        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Tampilkan modal sukses jika ada session success
                @if (session('success'))
                    document.getElementById("successMessage").innerText = "✅ {{ session('success') }}";
                    var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                    successModal.show();
                @endif

                // Tampilkan modal gagal jika ada session error
                @if (session('error'))
                    document.getElementById("errorMessage").innerText = "❌ {{ session('error') }}";
                    var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                    errorModal.show();
                @endif
            });

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
            $(document).ready(function() {
                $('#inventory_id, #jml_brg').on('change', function() {
                    let selectedOption = $('#inventory_id option:selected');
                    let hargaSatuan = parseFloat(selectedOption.data('harga')) || 0;
                    let jumlahDigunakan = parseInt($('#jml_brg').val()) || 0;
                    $('#harga_total').val(hargaSatuan * jumlahDigunakan);
                });
            });
        </script>

        
    @endsection
