<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bayar tagihan Pelanggan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .copy-btn {
            cursor: pointer;
            color: blue;
            text-decoration: underline;
            border: none;
            background: none;
            padding: 0;
            font-size: 0.9em;
        }
    </style>
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Angka berhasil disalin ke clipboard!');
            }).catch(err => {
                alert('Gagal menyalin teks: ' + err);
            });
        }

        function showDetail(numbers) {
            const container = document.getElementById('detailContainer');
            container.innerHTML = '';

            numbers.forEach((num, index) => {
                if (index % 5 === 0) {
                    const row = document.createElement('div');
                    row.className = 'row mb-3';
                    container.appendChild(row);
                }

                const col = document.createElement('div');
                col.className = 'col-md-6';
                col.innerHTML = `
                    <div class="p-2 border text-center">
                        <strong>${index + 1}.</strong> ${num}
                        <button onclick="copyToClipboard('${num}')" class="btn btn-sm btn-link copy-btn">Salin</button>
                    </div>`;
                container.lastChild.appendChild(col);
            });

            const modal = new bootstrap.Modal(document.getElementById('detailModal'));
            modal.show();
        }

        function confirmDelete(quantity) {
            return confirm(`Anda akan menghapus data dengan jumlah angka: ${quantity}. Lanjutkan?`);
        }
    </script>
</head>

<br>
<br>
<body>
    <div class="container">
        <h4 class="mb-4 mt-5 text-center">Bayar Tagihan Pelanggan</h4> <br>



        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if (session('alert'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('alert') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif



        <form action="{{ route('pembayaran_mudah.admin') }}">
            <div class="form-group d-flex">
                <input type="text" name="q" class="form-control me-2 "
                    placeholder="Cari berdasarkan ID atau Nama" value="{{ $query ?? '' }}">


                <button type="submit" class="btn btn-primary w-50 ml-2">Cari / Refresh</button>

            </div>
        </form>




        <div class="mt-4">
            @if (!$query_cari)
                <p class="text-muted">Silakan masukkan ID atau Nama untuk mencari data pelanggan.</p>


                <div>
                @elseif($pelanggan->isEmpty())
                    <p class="text-muted">Tidak ditemukan hasil untuk "{{ $query_cari }}"</p>
                @else
                    <table class="table table-bordered table-responsive"
                        style="color: black; width: 100%; font-size: 0.9em; table-layout: fixed;">
                        <thead class="table table-primary" style="color: black;">
                            <tr>
                                <th style="width: 1%; padding: 1px;" class="text-center">No</th>
                                <th style="width: 1%; padding: 1px; " class="text-center">ID Pelanggan</th>
                                <th style="width: 1%; padding: 1px; " class="text-center">Nama</th>
                                <th style="width: 1%; padding: 1px; " class="text-center">Alamat</th>
                                <th style="width: 1%; padding: 1px; " class="text-center">Harga</th>
                                <th style="width: 1%; padding: 1px; " class="text-center">Tanggal Tagih</th>
                                <th style="width: 1%; padding: 1px; " class="text-center">Status Pembayaran</th>
                                <th style="width: 1%; padding: 1px; " class="text-center">Bayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pelanggan as $no => $item)
                                <tr>
                                    <td style="padding: 1px; " class="text-center">
                                        {{ ($pelanggan->currentPage() - 1) * $pelanggan->perPage() + $loop->iteration }}
                                    </td>
                                    <td style="padding: 1px; " class="text-center">{{ $item->id_plg }}</td>
                                    <td style="padding: 2px; " >{{ $item->nama_plg }}</td>
                                    <td style="padding: 2px; " >{{ $item->alamat_plg }}</td>
                                    <td style="padding: 1px; " class="text-center">{{ number_format($item->harga_paket, 0, ',', '.') }}</td>
                                    <td style="padding: 1px; " class="text-center">{{ $item->tgl_tagih_plg }}</td>
                                    <td style="padding: 1px; " class="text-center">
                                        {{ optional($item->pembayaranTerakhir)->tanggal_pembayaran
                                            ? \Carbon\Carbon::parse($item->pembayaranTerakhir->tanggal_pembayaran)->locale('id')->isoFormat('MMMM Y')
                                            : '-' }}
                                    </td>
                                    <td style="padding: 0; margin: 0; text-align: center;">
                                        <a href="#" class="btn btn-success btn-xs"
                                            style="padding: 2px 5px; font-size: 0.75em;"
                                            onclick="showBayarModal({{ $item->id }}, '{{ $item->nama_plg }}', {{ $item->harga_paket }})"><img
                                                src="{{ asset('asset/img/icon/bayar.png') }}"
                                                style="height : 30px; width : 30px; " alt=""></a>
                                    </td>




                                    <!-- Modal Bayar -->
                                    <div class="modal fade" id="bayarModal" tabindex="-1"
                                        aria-labelledby="bayarModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="bayarModalLabel">Pembayaran</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <!-- Modal Form -->
                                                <form id="bayarForm">
                                                    @csrf
                                                    <input type="hidden" name="id" id="pelangganId">
                                                    <div class="modal-body">
                                                        <!-- Input Tanggal Pembayaran -->

                                                        <div class="mb-3">
                                                            <label for="tanggal_pembayaran" class="form-label">Untuk
                                                                Pembayaran
                                                                <label for="tanggal_pembayaran" class="form-label">Untuk
                                                                    Pembayaran
                                                                    Bulan</label>
                                                                <input type="month" class="form-select"
                                                                    id="tanggal_pembayaran" name="tanggal_pembayaran"
                                                                    placeholder="Pilih bulan">
                                                        </div>



                                                        <div class="mb-3">
                                                            <label for="metodeTransaksi" class="form-label">Metode
                                                                Transaksi</label>
                                                            <select class="form-select" id="metodeTransaksi"
                                                                name="metode_transaksi" required>
                                                                <option value="">Pilih metode</option>
                                                                <option value="TF">TF</option>
                                                                <option value="CASH">KANTOR</option>

                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="untuk_pembayaran" class="form-label">Status
                                                                Pembayaran</label>
                                                            <select class="form-select" id="untuk_pembayaran"
                                                                name="untuk_pembayaran" required>
                                                                <option value="">Pilih Pembayaran</option>
                                                                <option value="tagihan">Tagihan </option>
                                                                <option value="piutang">Piutang </option>
                                                                <option value="PSB">PSB </option>

                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="keterangan_plg" class="form-label">Keterangan
                                                                Pembayaran Pelanggan</label>
                                                            <input type="text" class="form-control"
                                                                id="keterangan_plg" name="keterangan_plg">
                                                        </div>

                                                        <!-- Detail Pembayaran -->
                                                        <div class="mb-3">
                                                            <p id="pembayaranDetails"></p>
                                                        </div>
                                                    </div>

                                                    <!-- Modal Footer -->
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Bayar</button>
                                                    </div>
                                                </form>


                                            </div>
                                        </div>
                                    </div>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>


            @endif
        </div>

    </div>



    <script>
        function showBayarModal(id, namaPlg, hargaPaket) {
            document.getElementById('pelangganId').value = id;
            document.getElementById('pembayaranDetails').innerText =
                `Nama Pelanggan: ${namaPlg}\nHarga Paket: Rp. ${hargaPaket}`;

            var form = document.getElementById('bayarForm');
            form.action = `/pelanggan/${id}/bayar`; // Set action URL with the ID

            var bayarModal = new bootstrap.Modal(document.getElementById('bayarModal'));
            bayarModal.show();
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
